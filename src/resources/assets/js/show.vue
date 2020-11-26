<script>
import CandidatesApi from './api/annotationCandidate';
import CandidatesImageGrid from './components/candidatesImageGrid';
import JobApi from './api/maiaJob';
import ProposalsApi from './api/trainingProposal';
import ProposalsImageGrid from './components/proposalsImageGrid';
import RefineCandidatesCanvas from './components/refineCandidatesCanvas';
import RefineCandidatesTab from './components/refineCandidatesTab';
import RefineCanvas from './components/refineCanvas';
import RefineProposalsTab from './components/refineProposalsTab';
import SelectCandidatesTab from './components/selectCandidatesTab';
import SelectProposalsTab from './components/selectProposalsTab';
import {handleErrorResponse} from './import';
import {ImagesStore} from './import';
import {Keyboard} from './import';
import {LoaderMixin} from './import';
import {Messages} from './import';
import {SidebarTab} from './import';
import {Sidebar} from './import';

// Proposals = Training Proposals
// Candidates = Annotation Candidates

// We have to take very great care from preventing Vue to make the training proposals
// and annotation candidates fully reactive. These can be huge arrays and Vue is not
// fast enough to ensure a fluid UX if they are fully reactive.
let PROPOSALS = [];
let PROPOSALS_BY_ID = {};
let CANDIDATES = [];
let CANDIDATES_BY_ID = {};

/**
 * View model for the main view of a MAIA job
 */
export default {
    mixins: [LoaderMixin],
    components: {
        sidebar: Sidebar,
        sidebarTab: SidebarTab,
        selectProposalsTab: SelectProposalsTab,
        proposalsImageGrid: ProposalsImageGrid,
        refineProposalsTab: RefineProposalsTab,
        refineCanvas: RefineCanvas,
        refineCandidatesCanvas: RefineCandidatesCanvas,
        selectCandidatesTab: SelectCandidatesTab,
        candidatesImageGrid: CandidatesImageGrid,
        refineCandidatesTab: RefineCandidatesTab,
    },
    data() {
        return {
            job: null,
            states: null,
            labelTrees: [],
            visitedSelectProposalsTab: false,
            visitedRefineProposalsTab: false,
            visitedSelectCandidatesTab: false,
            visitedRefineCandidatesTab: false,
            openTab: 'info',

            fetchProposalsPromise: null,
            hasProposals: false,
            // Track these manually and not via a computed property because the number of
            // training proposals can be huge.
            selectedProposalIds: {},
            seenProposalIds: {},
            lastSelectedProposal: null,
            currentProposalImage: null,
            currentProposalImageIndex: null,
            currentProposals: [],
            currentProposalsById: {},
            focussedProposal: null,
            proposalAnnotationCache: {},

            fetchCandidatesPromise: null,
            hasCandidates: false,
            selectedCandidateIds: {},
            convertedCandidateIds: {},
            lastSelectedCandidate: null,
            currentCandidateImage: null,
            currentCandidateImageIndex: null,
            currentCandidates: [],
            currentCandidatesById: {},
            focussedCandidate: null,
            candidateAnnotationCache: {},
            selectedLabel: null,

            // Increasing counter to use for sorting of selected proposals and
            // candidates. If a proposal/candidate is selected it should always be sorted
            // after all previously selected ones, regardless of it's ID or position
            // in the data returned from the API.
            sequenceCounter: 0,
        };
    },
    computed: {
        infoTabOpen() {
            return this.openTab === 'info';
        },
        selectProposalsTabOpen() {
            return this.openTab === 'select-proposals';
        },
        refineProposalsTabOpen() {
            return this.openTab === 'refine-proposals';
        },
        selectCandidatesTabOpen() {
            return this.openTab === 'select-candidates';
        },
        refineCandidatesTabOpen() {
            return this.openTab === 'refine-candidates';
        },
        isInTrainingProposalState() {
            return this.job.state_id === this.states['training-proposals'];
        },
        isInAnnotationCandidateState() {
            return this.job.state_id === this.states['annotation-candidates'];
        },

        proposals() {
            if (this.hasProposals) {
                return PROPOSALS;
            }

            return [];
        },
        selectedProposals() {
            let selectedIds = this.selectedProposalIds;

            return Object.keys(selectedIds)
                .map((id) => PROPOSALS_BY_ID[id])
                // Sort by image ID first and the proposals of the same image by
                // their assigned sequence ID. This way proposals that were selected
                // during refinement are always focussed next.
                .sort(function (a, b) {
                    if (a.image_id === b.image_id) {
                        return selectedIds[a.id] - selectedIds[b.id];
                    }

                    return a.image_id - b.image_id;
                });
        },
        // The thumbnails of unselected proposals are deleted when the job advances
        // to instance segmentation so we only want to show the selected proposals
        // when this happened.
        proposalsForSelectView() {
            if (this.isInTrainingProposalState) {
                return this.proposals;
            } else {
                return this.selectedProposals;
            }
        },
        hasSelectedProposals() {
            return this.selectedProposals.length > 0;
        },
        proposalImageIds() {
            let tmp = {};
            this.proposals.forEach((p) => tmp[p.image_id] = undefined);

            return Object.keys(tmp).map((id) => parseInt(id, 10));
        },
        currentProposalImageId() {
            return this.proposalImageIds[this.currentProposalImageIndex];
        },
        nextProposalImageIndex() {
            return (this.currentProposalImageIndex + 1) % this.proposalImageIds.length;
        },
        nextProposalImageId() {
            return this.proposalImageIds[this.nextProposalImageIndex];
        },
        nextFocussedProposalImageId() {
            if (this.nextFocussedProposal) {
                return this.nextFocussedProposal.image_id;
            }

            return this.nextProposalImageId;
        },
        previousProposalImageIndex() {
          return (this.currentProposalImageIndex - 1 + this.proposalImageIds.length) % this.proposalImageIds.length;
        },
        previousProposalImageId() {
            return this.proposalImageIds[this.previousProposalImageIndex];
        },
        hasCurrentProposalImage() {
            return this.currentProposalImage !== null;
        },
        currentSelectedProposals() {
            return this.currentProposals.filter(
                (p) => this.selectedProposalIds.hasOwnProperty(p.id)
            );
        },
        currentUnselectedProposals() {
            return this.currentProposals.filter(
                (p) => !this.selectedProposalIds.hasOwnProperty(p.id)
            );
        },
        previousFocussedProposal() {
            let index = (this.selectedProposals.indexOf(this.focussedProposal) - 1 + this.selectedProposals.length) % this.selectedProposals.length;

            return this.selectedProposals[index];
        },
        nextFocussedProposal() {
            let index = (this.selectedProposals.indexOf(this.focussedProposal) + 1) % this.selectedProposals.length;

            return this.selectedProposals[index];
        },
        // The focussed training proposal might change while the refine tab tool is
        // closed but it should be updated only when it it opened again. Else the
        // canvas would not update correctly.
        focussedProposalToShow() {
            if (this.refineProposalsTabOpen) {
                return this.focussedProposal;
            }

            return null;
        },
        focussedProposalArray() {
            if (this.focussedProposalToShow) {
                return this.currentSelectedProposals.filter(
                    (p) => p.id === this.focussedProposalToShow.id
                );
            }

            return [];
        },
        selectedAndSeenProposals() {
            return this.selectedProposals.filter(
                (p) => this.seenProposalIds.hasOwnProperty(p.id)
            );
        },

        candidates() {
            if (this.hasCandidates) {
                return CANDIDATES;
            }

            return [];
        },
        selectedCandidates() {
            let selectedIds = this.selectedCandidateIds;

            return Object.keys(selectedIds)
                .map((id) => CANDIDATES_BY_ID[id])
                // Sort by image ID first and the candidates of the same image by
                // their assigned sequence ID. This way candidates that were selected
                // during refinement are always focussed next.
                .sort(function (a, b) {
                    if (a.image_id === b.image_id) {
                        return selectedIds[a.id] - selectedIds[b.id];
                    }

                    return a.image_id - b.image_id;
                });
        },
        hasSelectedCandidates() {
            return this.selectedCandidates.length > 0;
        },
        candidateImageIds() {
            let tmp = {};
            this.candidates.forEach((p) => tmp[p.image_id] = undefined);

            return Object.keys(tmp).map((id) => parseInt(id, 10));
        },
        currentCandidateImageId() {
            return this.candidateImageIds[this.currentCandidateImageIndex];
        },
        nextCandidateImageIndex() {
            return (this.currentCandidateImageIndex + 1) % this.candidateImageIds.length;
        },
        nextCandidateImageId() {
            return this.candidateImageIds[this.nextCandidateImageIndex];
        },
        previousCandidateImageIndex() {
          return (this.currentCandidateImageIndex - 1 + this.candidateImageIds.length) % this.candidateImageIds.length;
        },
        previousCandidateImageId() {
            return this.candidateImageIds[this.previousCandidateImageIndex];
        },
        hasCurrentCandidateImage() {
            return this.currentCandidateImage !== null;
        },
        currentSelectedCandidates() {
            return this.currentCandidates.filter(
                (p) => this.selectedCandidateIds.hasOwnProperty(p.id)
            );
        },
        currentUnselectedCandidates() {
            return this.currentCandidates.filter(
                (p) => !this.selectedCandidateIds.hasOwnProperty(p.id) && !this.convertedCandidateIds.hasOwnProperty(p.id)
            );
        },
        currentConvertedCandidates() {
            return this.currentCandidates.filter(
                (p) => this.convertedCandidateIds.hasOwnProperty(p.id)
            );
        },
        previousFocussedCandidate() {
            let index = (this.selectedCandidates.indexOf(this.focussedCandidate) - 1 + this.selectedCandidates.length) % this.selectedCandidates.length;

            return this.selectedCandidates[index];
        },
        nextFocussedCandidate() {
            let index = (this.selectedCandidates.indexOf(this.focussedCandidate) + 1) % this.selectedCandidates.length;

            return this.selectedCandidates[index];
        },
        nextFocussedCandidateImageId() {
            if (this.nextFocussedCandidate) {
                return this.nextFocussedCandidate.image_id;
            }

            return this.nextCandidateImageId;
        },
        // The focussed training proposal might change while the refine tab tool is
        // closed but it should be updated only when it it opened again. Else the
        // canvas would not update correctly.
        focussedCandidateToShow() {
            if (this.refineCandidatesTabOpen) {
                return this.focussedCandidate;
            }

            return null;
        },
        focussedCandidateArray() {
            if (this.focussedCandidateToShow) {
                return this.currentSelectedCandidates.filter(
                    (p) => p.id === this.focussedCandidateToShow.id
                );
            }

            return [];
        },
    },
    methods: {
        handleSidebarToggle() {
            this.$nextTick(() => {
                if (this.$refs.proposalsImageGrid) {
                    this.$refs.proposalsImageGrid.$emit('resize');
                }
                if (this.$refs.candidatesImageGrid) {
                    this.$refs.candidatesImageGrid.$emit('resize');
                }
            });
        },
        handleTabOpened(tab) {
            this.openTab = tab;
        },
        setProposals(response) {
            PROPOSALS = response.body;

            PROPOSALS.forEach((p) => {
                PROPOSALS_BY_ID[p.id] = p;
                this.setSelectedProposalId(p);
            });

            this.hasProposals = PROPOSALS.length > 0;
        },
        fetchProposals() {
            if (!this.fetchProposalsPromise) {
                this.startLoading();

                this.fetchProposalsPromise = JobApi.getTrainingProposals({id: this.job.id});

                this.fetchProposalsPromise.then(this.setProposals, handleErrorResponse)
                    .finally(this.finishLoading);
            }

            return this.fetchProposalsPromise;
        },
        openRefineProposalsTab() {
            this.openTab = 'refine-proposals';
        },
        openRefineCandidatesTab() {
            this.openTab = 'refine-candidates';
        },
        updateSelectProposal(proposal, selected) {
            proposal.selected = selected;
            this.setSelectedProposalId(proposal);
            let promise = ProposalsApi.update({id: proposal.id}, {selected: selected});

            promise.catch((response) => {
                handleErrorResponse(response);
                proposal.selected = !selected;
                this.setSelectedProposalId(proposal);
            });

            return promise;
        },
        setSelectedProposalId(proposal) {
            if (proposal.selected) {
                Vue.set(this.selectedProposalIds, proposal.id, this.getSequenceId());
            } else {
                Vue.delete(this.selectedProposalIds, proposal.id);
            }
        },
        setSeenProposalId(p) {
            Vue.set(this.seenProposalIds, p.id, true);
        },
        fetchProposalAnnotations(id) {
            if (!this.proposalAnnotationCache.hasOwnProperty(id)) {
                this.proposalAnnotationCache[id] = JobApi
                    .getTrainingProposalPoints({jobId: this.job.id, imageId: id})
                    .then(this.parseAnnotations);
            }

            return this.proposalAnnotationCache[id];
        },
        parseAnnotations(response) {
            return Object.keys(response.body).map(function (id) {
                return {
                    id: parseInt(id, 10),
                    shape: 'Circle',
                    points: response.body[id],
                };
            });
        },
        setCurrentProposalImageAndAnnotations(args) {
            this.currentProposalImage = args[0];
            this.currentProposals = args[1];
            this.currentProposalsById = {};
            this.currentProposals.forEach((p) => {
                this.currentProposalsById[p.id] = p;
            });
        },
        cacheNextProposalImage() {
            // Do nothing if there is only one image.
            if (this.currentProposalImageId !== this.nextFocussedProposalImageId) {
                ImagesStore.fetchImage(this.nextFocussedProposalImageId)
                    // Ignore errors in this case. The application will try to reload
                    // the data again if the user switches to the respective image
                    // and display the error message then.
                    .catch(function () {});
            }
        },
        cacheNextProposalAnnotations() {
            // Do nothing if there is only one image.
            if (this.currentProposalImageId !== this.nextFocussedProposalImageId) {
                this.fetchProposalAnnotations(this.nextFocussedProposalImageId)
                    // Ignore errors in this case. The application will try to reload
                    // the data again if the user switches to the respective image
                    // and display the error message then.
                    .catch(function () {});
            }
        },
        handlePreviousProposalImage() {
            this.currentProposalImageIndex = this.previousProposalImageIndex;
        },
        handlePreviousProposal() {
            if (this.previousFocussedProposal) {
                this.focussedProposal = this.previousFocussedProposal;
            } else {
                this.handlePreviousProposalImage();
            }
        },
        handleNextProposalImage() {
            this.currentProposalImageIndex = this.nextProposalImageIndex;
        },
        handleNextProposal() {
            if (this.nextFocussedProposal) {
                this.focussedProposal = this.nextFocussedProposal;
            } else {
                this.handleNextProposalImage();
            }
        },
        handleRefineProposal(proposals) {
            Vue.Promise.all(proposals.map(this.updateProposalPoints))
                .catch(handleErrorResponse);
        },
        updateProposalPoints(proposal) {
            let toUpdate = this.currentProposalsById[proposal.id];
            return ProposalsApi.update({id: proposal.id}, {points: proposal.points})
                .then(function () {
                    toUpdate.points = proposal.points;
                });
        },
        focusProposalToShow() {
            if (this.focussedProposalToShow) {
                let p = this.currentProposalsById[this.focussedProposalToShow.id];
                if (p) {
                    this.$refs.refineProposalsCanvas.focusAnnotation(p, true, false);
                }
            }
        },
        handleSelectedProposal(proposal, event) {
            if (proposal.selected) {
                this.unselectProposal(proposal);
            } else {
                if (event.shiftKey && this.lastSelectedProposal) {
                    this.doForEachBetween(this.proposals, proposal, this.lastSelectedProposal, this.selectProposal);
                } else {
                    this.lastSelectedProposal = proposal;
                    this.selectProposal(proposal);
                }
            }
        },
        selectProposal(proposal) {
            this.updateSelectProposal(proposal, true)
                .then(this.maybeInitFocussedProposal);
        },
        unselectProposal(proposal) {
            let next = this.nextFocussedProposal;
            this.updateSelectProposal(proposal, false)
                .bind(this)
                .then(function () {
                    this.maybeUnsetFocussedProposal(proposal, next);
                });
        },
        maybeInitFocussedProposal() {
            if (!this.focussedProposal && this.hasSelectedProposals) {
                this.focussedProposal = this.selectedProposals[0];
            }
        },
        maybeUnsetFocussedProposal(proposal, next) {
            if (this.focussedProposal && this.focussedProposal.id === proposal.id) {
                if (next && next.id !== proposal.id) {
                    this.focussedProposal = next;
                } else {
                    this.focussedProposal = null;
                }
            }
        },
        maybeInitCurrentProposalImage() {
            if (this.currentProposalImageIndex === null) {
                this.currentProposalImageIndex = 0;
            }
        },
        maybeInitCurrentCandidateImage() {
            if (this.currentCandidateImageIndex === null) {
                this.currentCandidateImageIndex = 0;
            }
        },
        handleLoadingError(message) {
            Messages.danger(message);
        },
        setSelectedCandidateId(candidate) {
            if (candidate.label && !candidate.annotation_id) {
                Vue.set(this.selectedCandidateIds, candidate.id, this.getSequenceId());
            } else {
                Vue.delete(this.selectedCandidateIds, candidate.id);
            }
        },
        setConvertedCandidateId(candidate) {
            if (candidate.annotation_id) {
                Vue.set(this.convertedCandidateIds, candidate.id, candidate.annotation_id);
            } else {
                Vue.delete(this.convertedCandidateIds, candidate.id);
            }
        },
        setCandidates(response) {
            CANDIDATES = response.body;

            CANDIDATES.forEach((p) => {
                CANDIDATES_BY_ID[p.id] = p;
                this.setSelectedCandidateId(p);
                this.setConvertedCandidateId(p);
            });

            this.hasCandidates = CANDIDATES.length > 0;
        },
        fetchCandidates(force) {
            if (!this.fetchCandidatesPromise || force) {
                this.startLoading();

                this.fetchCandidatesPromise = JobApi.getAnnotationCandidates({id: this.job.id});

                this.fetchCandidatesPromise.then(this.setCandidates, handleErrorResponse)
                    .finally(this.finishLoading);
            }

            return this.fetchCandidatesPromise;
        },
        handleSelectedCandidate(candidate, event) {
            if (candidate.label) {
                this.unselectCandidate(candidate);
            } else {
                if (event.shiftKey && this.lastSelectedCandidate && this.selectedLabel) {
                    this.doForEachBetween(this.candidates, candidate, this.lastSelectedCandidate, this.selectCandidate);
                } else {
                    this.lastSelectedCandidate = candidate;
                    this.selectCandidate(candidate);
                }
            }
        },
        selectCandidate(candidate) {
            if (this.selectedLabel) {
                // Do not select candidates that have been converted.
                if (!candidate.annotation_id) {
                    this.updateSelectCandidate(candidate, this.selectedLabel)
                        .then(this.maybeInitFocussedCandidate);
                }
            } else {
                Messages.info('Please select a label first.');
            }
        },
        unselectCandidate(candidate) {
            let next = this.nextFocussedCandidate;
            this.updateSelectCandidate(candidate, null)
                .bind(this)
                .then(function () {
                    this.maybeUnsetFocussedCandidate(candidate, next);
                });
        },
        updateSelectCandidate(candidate, label) {
            let oldLabel = candidate.label;
            candidate.label = label;
            this.setSelectedCandidateId(candidate);
            let labelId = label ? label.id : null;
            let promise = CandidatesApi.update({id: candidate.id}, {label_id: labelId});

            promise.catch((response) => {
                handleErrorResponse(response);
                candidate.label = oldLabel;
                this.setSelectedCandidateId(candidate);
            });

            return promise;
        },
        fetchCandidateAnnotations(id) {
            if (!this.candidateAnnotationCache.hasOwnProperty(id)) {
                this.candidateAnnotationCache[id] = JobApi
                    .getAnnotationCandidatePoints({jobId: this.job.id, imageId: id})
                    .then(this.parseAnnotations);
            }

            return this.candidateAnnotationCache[id];
        },
        setCurrentCandidateImageAndAnnotations(args) {
            this.currentCandidateImage = args[0];
            this.currentCandidates = args[1];
            this.currentCandidatesById = {};
            this.currentCandidates.forEach((p) => this.currentCandidatesById[p.id] = p);
        },
        handlePreviousCandidateImage() {
            this.currentCandidateImageIndex = this.previousCandidateImageIndex;
        },
        handlePreviousCandidate() {
            if (this.previousFocussedCandidate) {
                this.focussedCandidate = this.previousFocussedCandidate;
            } else {
                this.handlePreviousCandidateImage();
            }
        },
        handleNextCandidateImage() {
            this.currentCandidateImageIndex = this.nextCandidateImageIndex;
        },
        handleNextCandidate() {
            if (this.nextFocussedCandidate) {
                this.focussedCandidate = this.nextFocussedCandidate;
            } else {
                this.handleNextCandidateImage();
            }
        },
        focusCandidateToShow() {
            if (this.focussedCandidateToShow) {
                let p = this.currentCandidatesById[this.focussedCandidateToShow.id];
                if (p) {
                    this.$refs.refineCandidatesCanvas.focusAnnotation(p, true, false);
                }
            }
        },
        maybeInitFocussedCandidate() {
            if (!this.focussedCandidate && this.hasSelectedCandidates) {
                this.focussedCandidate = this.selectedCandidates[0];
            }
        },
        maybeUnsetFocussedCandidate(proposal, next) {
            if (this.focussedCandidate && this.focussedCandidate.id === proposal.id) {
                if (next && next.id !== proposal.id) {
                    this.focussedCandidate = next;
                } else {
                    this.focussedCandidate = null;
                }
            }
        },
        handleSelectedLabel(label) {
            this.selectedLabel = label;
        },
        doForEachBetween(all, first, second, callback) {
            let index1 = all.indexOf(first);
            let index2 = all.indexOf(second);
            // The second element is exclusive so shift the lower index one up if
            // second is before first or the upper index down if second is after
            // first.
            if (index2 < index1) {
                let tmp = index2;
                index2 = index1;
                index1 = tmp + 1;
            } else {
                index2 -= 1;
            }

            for (let i = index1; i <= index2; i++) {
                callback(all[i]);
            }
        },
        cacheNextCandidateImage() {
            // Do nothing if there is only one image.
            if (this.currentCandidateImageId !== this.nextFocussedCandidateImageId) {
                ImagesStore.fetchImage(this.nextFocussedCandidateImageId)
                    // Ignore errors in this case. The application will try to reload
                    // the data again if the user switches to the respective image
                    // and display the error message then.
                    .catch(function () {});
            }
        },
        cacheNextCandidateAnnotations() {
            // Do nothing if there is only one image.
            if (this.currentCandidateImageId !== this.nextFocussedCandidateImageId) {
                this.fetchCandidateAnnotations(this.nextFocussedCandidateImageId)
                    // Ignore errors in this case. The application will try to reload
                    // the data again if the user switches to the respective image
                    // and display the error message then.
                    .catch(function () {});
            }
        },
        handleRefineCandidate(candidates) {
            Vue.Promise.all(candidates.map(this.updateCandidatePoints))
                .catch(handleErrorResponse);
        },
        updateCandidatePoints(candidate) {
            let toUpdate = this.currentCandidatesById[candidate.id];
            return CandidatesApi.update({id: candidate.id}, {points: candidate.points})
                .then(() => toUpdate.points = candidate.points);
        },
        handleConvertCandidates() {
            this.startLoading();
            JobApi.convertAnnotationCandidates({id: this.job.id}, {})
                .then(this.waitForConvertedCandidates);
        },
        waitForConvertedCandidates() {
            let wait = () => {
                window.setTimeout(() => {
                    JobApi.convertingAnnotationCandidates({id: this.job.id})
                        .then(wait, this.handleConvertedCandidates);
                }, 2000);
            };

            wait();
        },
        handleConvertedCandidates(response) {
            if (response.status === 404) {
                return this.fetchCandidates(true)
                    .then(() => {
                        let next = this.nextFocussedCandidate;
                        this.candidates.map(c => this.maybeUnsetFocussedCandidate(c, next));
                    })
                    .then(this.finishLoading);
            } else {
                handleErrorResponse(response);
                this.finishLoading();
            }
        },
        getSequenceId() {
            return this.sequenceCounter++;
        },
    },
    watch: {
        selectProposalsTabOpen(open) {
            this.visitedSelectProposalsTab = true;
            if (open) {
                Keyboard.setActiveSet('select-proposals');
            }
        },
        refineProposalsTabOpen(open) {
            this.visitedRefineProposalsTab = true;
            if (open) {
                Keyboard.setActiveSet('refine-proposals');
                this.maybeInitFocussedProposal();
            }
        },
        selectCandidatesTabOpen(open) {
            this.visitedSelectCandidatesTab = true;
            if (open) {
                Keyboard.setActiveSet('select-candidates');
            }
        },
        refineCandidatesTabOpen(open) {
            this.visitedRefineCandidatesTab = true;
            if (open) {
                Keyboard.setActiveSet('refine-candidates');
            }
        },
        visitedSelectProposalsTab() {
            this.fetchProposals();
        },
        visitedRefineProposalsTab() {
            this.fetchProposals()
                .then(this.maybeInitFocussedProposal)
                .then(this.maybeInitCurrentProposalImage);
        },
        visitedSelectCandidatesTab() {
            this.fetchCandidates();
        },
        visitedRefineCandidatesTab() {
            this.fetchCandidates()
                .then(this.maybeInitFocussedCandidate)
                .then(this.maybeInitCurrentCandidateImage);
        },
        currentProposalImageId(id) {
            if (id) {
                this.startLoading();
                Vue.Promise.all([
                        ImagesStore.fetchAndDrawImage(id),
                        this.fetchProposalAnnotations(id),
                        this.fetchProposals(),
                    ])
                    .then(this.setCurrentProposalImageAndAnnotations)
                    .then(this.focusProposalToShow)
                    .then(this.cacheNextProposalImage)
                    .then(this.cacheNextProposalAnnotations)
                    .catch(this.handleLoadingError)
                    .finally(this.finishLoading);
            } else {
                this.setCurrentProposalImageAndAnnotations([null, null]);
            }
        },
        focussedProposalToShow(proposal, old) {
            if (proposal) {
                if (old && old.image_id === proposal.image_id) {
                    this.focusProposalToShow();
                } else {
                    this.currentProposalImageIndex = this.proposalImageIds.indexOf(proposal.image_id);
                }
                this.setSeenProposalId(proposal);
            }
        },
        currentCandidateImageId(id) {
            if (id) {
                this.startLoading();
                Vue.Promise.all([
                        ImagesStore.fetchAndDrawImage(id),
                        this.fetchCandidateAnnotations(id),
                        this.fetchCandidates(),
                    ])
                    .then(this.setCurrentCandidateImageAndAnnotations)
                    .then(this.focusCandidateToShow)
                    .then(this.cacheNextCandidateImage)
                    .then(this.cacheNextCandidateAnnotations)
                    .catch(this.handleLoadingError)
                    .finally(this.finishLoading);
            } else {
                this.setCurrentCandidateImageAndAnnotations([null, null]);
            }
        },
        focussedCandidateToShow(candidate, old) {
            if (candidate) {
                if (old && old.image_id === candidate.image_id) {
                    this.focusCandidateToShow();
                } else {
                    this.currentCandidateImageIndex = this.candidateImageIds.indexOf(candidate.image_id);
                }
            }
        },
    },
    created() {
        this.job = biigle.$require('maia.job');
        this.states = biigle.$require('maia.states');
        this.labelTrees = biigle.$require('maia.labelTrees');
    },
};
</script>
