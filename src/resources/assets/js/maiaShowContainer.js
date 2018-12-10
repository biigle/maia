/**
 * View model for the main view of a MAIA job
 */
biigle.$viewModel('maia-show-container', function (element) {
    var job = biigle.$require('maia.job');
    var states = biigle.$require('maia.states');
    var labelTrees = biigle.$require('maia.labelTrees');
    var maiaJobApi = biigle.$require('maia.api.maiaJob');
    var maiaAnnotationApi = biigle.$require('maia.api.maiaAnnotation');
    var messages = biigle.$require('messages.store');
    var imagesStore = biigle.$require('annotations.stores.images');

    // Proposals = Training Proposals
    // Candidates = Annotation Candidates

    // We have to take very great care from preventing Vue to make the training proposals
    // and annotation candidates fully reactive. These can be huge arrays and Vue is not
    // fast enough to ensure a fluid UX if they are fully reactive.
    var PROPOSALS = [];
    var PROPOSALS_BY_ID = {};
    var CANDIDATES = [];
    var CANDIDATES_BY_ID = {};

    new Vue({
        el: element,
        mixins: [biigle.$require('core.mixins.loader')],
        components: {
            sidebar: biigle.$require('annotations.components.sidebar'),
            sidebarTab: biigle.$require('core.components.sidebarTab'),
            selectProposalsTab: biigle.$require('maia.components.selectProposalsTab'),
            proposalsImageGrid: biigle.$require('maia.components.proposalsImageGrid'),
            refineProposalsTab: biigle.$require('maia.components.refineProposalsTab'),
            refineProposalsCanvas: biigle.$require('maia.components.refineProposalsCanvas'),
            selectCandidatesTab: biigle.$require('maia.components.selectCandidatesTab'),
            candidatesImageGrid: biigle.$require('maia.components.candidatesImageGrid'),
            refineCandidatesTab: biigle.$require('maia.components.refineCandidatesTab'),
        },
        data: {
            visitedSelectProposalsTab: false,
            visitedRefineProposalsTab: false,
            visitedSelectCandidatesTab: false,
            visitedRefineCandidatesTab: false,
            openTab: 'info',
            labelTrees: labelTrees,

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
            currentCandidateImage: null,
            currentCandidateImageIndex: null,
            currentCandidates: [],
            currentCandidatesById: {},
            candidateAnnotationCache: {},
        },
        computed: {
            infoTabOpen: function () {
                return this.openTab === 'info';
            },
            selectProposalsTabOpen: function () {
                return this.openTab === 'select-proposals';
            },
            refineProposalsTabOpen: function () {
                return this.openTab === 'refine-proposals';
            },
            selectCandidatesTabOpen: function () {
                return this.openTab === 'select-candidates';
            },
            refineCandidatesTabOpen: function () {
                return this.openTab === 'refine-candidates';
            },
            isInTrainingProposalState: function () {
                return job.state_id === states['training-proposals'];
            },
            isInAnnotationCandidateState: function () {
                return job.state_id === states['annotation-candidates'];
            },

            proposals: function () {
                if (this.hasProposals) {
                    return PROPOSALS;
                }

                return [];
            },
            selectedProposals: function () {
                return Object.keys(this.selectedProposalIds)
                    .map(function (id) {
                        return PROPOSALS_BY_ID[id];
                    });
            },
            // The thumbnails of unselected proposals are deleted when the job advances
            // to instance segmentation so we only want to show the selected proposals
            // when this happened.
            proposalsForSelectView: function () {
                if (this.isInTrainingProposalState) {
                    return this.proposals;
                } else {
                    return this.selectedProposals;
                }
            },
            hasSelectedProposals: function () {
                return this.selectedProposals.length > 0;
            },
            proposalImageIds: function () {
                var tmp = {};
                this.proposals.forEach(function (p) {
                    tmp[p.image_id] = undefined;
                });

                return Object.keys(tmp).map(function (id) {
                    return parseInt(id, 10);
                });
            },
            currentProposalImageId: function () {
                return this.proposalImageIds[this.currentProposalImageIndex];
            },
            nextProposalImageIndex: function () {
                return (this.currentProposalImageIndex + 1) % this.proposalImageIds.length;
            },
            nextProposalImageId: function () {
                return this.proposalImageIds[this.nextProposalImageIndex];
            },
            nextFocussedProposalImageId: function () {
                if (this.nextFocussedProposal) {
                    return this.nextFocussedProposal.image_id;
                }

                return this.nextProposalImageId;
            },
            previousProposalImageIndex: function () {
              return (this.currentProposalImageIndex - 1 + this.proposalImageIds.length) % this.proposalImageIds.length;
            },
            previousProposalImageId: function () {
                return this.proposalImageIds[this.previousProposalImageIndex];
            },
            hasCurrentProposalImage: function () {
                return this.currentProposalImage !== null;
            },
            currentSelectedProposals: function () {
                return this.currentProposals.filter(function (p) {
                    return this.selectedProposalIds.hasOwnProperty(p.id);
                }, this);
            },
            currentUnselectedProposals: function () {
                return this.currentProposals.filter(function (p) {
                    return !this.selectedProposalIds.hasOwnProperty(p.id);
                }, this);
            },
            previousFocussedProposal: function () {
                var index = (this.selectedProposals.indexOf(this.focussedProposal) - 1 + this.selectedProposals.length) % this.selectedProposals.length;

                return this.selectedProposals[index];
            },
            nextFocussedProposal: function () {
                var index = (this.selectedProposals.indexOf(this.focussedProposal) + 1) % this.selectedProposals.length;

                return this.selectedProposals[index];
            },
            // The focussed training proposal might change while the refine tab tool is
            // closed but it should be updated only when it it opened again. Else the
            // canvas would not update correctly.
            focussedProposalToShow: function () {
                if (this.refineProposalsTabOpen) {
                    return this.focussedProposal;
                }

                return null;
            },
            focussedProposalArray: function () {
                if (this.focussedProposalToShow) {
                    return this.currentSelectedProposals.filter(function (p) {
                        return p.id === this.focussedProposalToShow.id;
                    }, this);
                }

                return [];
            },
            selectedAndSeenProposals: function () {
                return this.selectedProposals.filter(function (p) {
                    return this.seenProposalIds.hasOwnProperty(p.id);
                }, this);
            },

            candidates: function () {
                if (this.hasCandidates) {
                    return CANDIDATES;
                }

                return [];
            },
            selectedCandidates: function () {
                return Object.keys(this.selectedCandidateIds)
                    .map(function (id) {
                        return CANDIDATES_BY_ID[id];
                    });
            },
            hasSelectedCandidates: function () {
                return this.selectedCandidates.length > 0;
            },
            candidateImageIds: function () {
                var tmp = {};
                this.candidates.forEach(function (p) {
                    tmp[p.image_id] = undefined;
                });

                return Object.keys(tmp).map(function (id) {
                    return parseInt(id, 10);
                });
            },
            currentCandidateImageId: function () {
                return this.candidateImageIds[this.currentCandidateImageIndex];
            },
            nextCandidateImageIndex: function () {
                return (this.currentCandidateImageIndex + 1) % this.candidateImageIds.length;
            },
            nextCandidateImageId: function () {
                return this.candidateImageIds[this.nextCandidateImageIndex];
            },
            // nextFocussedCandidateImageId: function () {
            //     if (this.nextFocussedCandidate) {
            //         return this.nextFocussedCandidate.image_id;
            //     }

            //     return this.nextCandidateImageId;
            // },
            previousCandidateImageIndex: function () {
              return (this.currentCandidateImageIndex - 1 + this.candidateImageIds.length) % this.candidateImageIds.length;
            },
            previousCandidateImageId: function () {
                return this.candidateImageIds[this.previousCandidateImageIndex];
            },
            hasCurrentCandidateImage: function () {
                return this.currentCandidateImage !== null;
            },
            currentSelectedCandidates: function () {
                return this.currentCandidates.filter(function (p) {
                    return this.selectedCandidateIds.hasOwnProperty(p.id);
                }, this);
            },
            currentUnselectedCandidates: function () {
                return this.currentCandidates.filter(function (p) {
                    return !this.selectedCandidateIds.hasOwnProperty(p.id);
                }, this);
            },
        },
        methods: {
            handleSidebarToggle: function () {
                this.$nextTick(function () {
                    if (this.$refs.proposalsImageGrid) {
                        this.$refs.proposalsImageGrid.$emit('resize');
                    }
                    if (this.$refs.candidatesImageGrid) {
                        this.$refs.candidatesImageGrid.$emit('resize');
                    }
                });
            },
            handleTabOpened: function (tab) {
                this.openTab = tab;
            },
            setProposals: function (response) {
                PROPOSALS = response.body;

                PROPOSALS.forEach(function (p) {
                    PROPOSALS_BY_ID[p.id] = p;
                    this.setSelectedProposalId(p);
                }, this);

                this.hasProposals = PROPOSALS.length > 0;
            },
            fetchProposals: function () {
                if (!this.fetchProposalsPromise) {
                    this.startLoading();

                    this.fetchProposalsPromise = maiaJobApi.getTrainingProposals({id: job.id});

                    this.fetchProposalsPromise.then(this.setProposals)
                        .catch(messages.handleErrorResponse)
                        .finally(this.finishLoading);
                }

                return this.fetchProposalsPromise;
            },
            openRefineProposalsTab: function () {
                this.openTab = 'refine-proposals';
            },
            openRefineCandidatesTab: function () {
                this.openTab = 'refine-candidates';
            },
            updateSelectProposal: function (proposal, selected) {
                proposal.selected = selected;
                this.setSelectedProposalId(proposal);
                var promise = maiaAnnotationApi.update({id: proposal.id}, {selected: selected});

                promise.catch(function (response) {
                    messages.handleErrorResponse(response);
                    proposal.selected = !selected;
                    this.setSelectedProposalId(proposal);
                });

                return promise;
            },
            setSelectedProposalId: function (p) {
                this.setSelectedAnnotation(p, this.selectedProposalIds);
            },
            setSeenProposalId: function (p) {
                Vue.set(this.seenProposalIds, p.id, true);
            },
            fetchProposalAnnotations: function (id) {
                if (!this.proposalAnnotationCache.hasOwnProperty(id)) {
                    this.proposalAnnotationCache[id] = maiaJobApi
                        .getTrainingProposalPoints({jobId: job.id, imageId: id})
                        .then(this.parseAnnotations);
                }

                return this.proposalAnnotationCache[id];
            },
            parseAnnotations: function (response) {
                return Object.keys(response.body).map(function (id) {
                    return {
                        id: parseInt(id, 10),
                        shape: 'Circle',
                        points: response.body[id],
                    };
                });
            },
            setCurrentProposalImageAndAnnotations: function (args) {
                this.currentProposalImage = args[0];
                this.currentProposals = args[1];
                this.currentProposalsById = {};
                this.currentProposals.forEach(function (p) {
                    this.currentProposalsById[p.id] = p;
                }, this);
            },
            cacheNextProposalImage: function () {
                // Do nothing if there is only one image.
                if (this.currentProposalImageId !== this.nextFocussedProposalImageId) {
                    imagesStore.fetchImage(this.nextFocussedProposalImageId)
                        // Ignore errors in this case. The application will try to reload
                        // the data again if the user switches to the respective image
                        // and display the error message then.
                        .catch(function () {});
                }
            },
            cacheNextProposalAnnotations: function () {
                // Do nothing if there is only one image.
                if (this.currentProposalImageId !== this.nextFocussedProposalImageId) {
                    this.fetchProposalAnnotations(this.nextFocussedProposalImageId)
                        // Ignore errors in this case. The application will try to reload
                        // the data again if the user switches to the respective image
                        // and display the error message then.
                        .catch(function () {});
                }
            },
            handlePreviousProposalImage: function () {
                this.currentProposalImageIndex = this.previousProposalImageIndex;
            },
            handlePreviousProposal: function () {
                if (this.previousFocussedProposal) {
                    this.focussedProposal = this.previousFocussedProposal;
                } else {
                    this.handlePreviousProposalImage();
                }
            },
            handleNextProposalImage: function () {
                this.currentProposalImageIndex = this.nextProposalImageIndex;
            },
            handleNextProposal: function () {
                if (this.nextFocussedProposal) {
                    this.focussedProposal = this.nextFocussedProposal;
                } else {
                    this.handleNextProposalImage();
                }
            },
            handleRefineProposal: function (proposals) {
                Vue.Promise.all(proposals.map(this.updateProposalPoints))
                    .catch(messages.handleErrorResponse);
            },
            updateProposalPoints: function (proposal) {
                var toUpdate = this.currentProposalsById[proposal.id];
                return maiaAnnotationApi
                    .update({id: proposal.id}, {points: proposal.points})
                    .then(function () {
                        toUpdate.points = proposal.points;
                    });
            },
            focusProposalToShow: function () {
                if (this.focussedProposalToShow) {
                    var p = this.currentProposalsById[this.focussedProposalToShow.id];
                    if (p) {
                        this.$refs.refineProposalsCanvas.focusAnnotation(p, true, false);
                    }
                }
            },
            handleSelectedProposal: function (proposal, event) {
                if (proposal.selected) {
                    this.unselectProposal(proposal);
                } else {
                    if (event.shiftKey && this.lastSelectedProposal) {
                        this.selectAllProposalsBetween(proposal, this.lastSelectedProposal);
                    } else {
                        this.lastSelectedProposal = proposal;
                        this.selectProposal(proposal);
                    }
                }
            },
            selectAllProposalsBetween: function (first, second) {
                var index1 = this.proposals.indexOf(first);
                var index2 = this.proposals.indexOf(second);
                if (index2 < index1) {
                    var tmp = index2;
                    index2 = index1;
                    index1 = tmp;
                }

                for (var i = index1 + 1; i <= index2; i++) {
                    this.selectProposal(this.proposals[i]);
                }
            },
            selectProposal: function (proposal) {
                this.updateSelectProposal(proposal, true)
                    .then(this.maybeInitFocussedProposal);
            },
            unselectProposal: function (proposal) {
                var next = this.nextFocussedProposal;
                this.updateSelectProposal(proposal, false)
                    .bind(this)
                    .then(function () {
                        this.maybeUnsetFocussedProposal(proposal, next);
                    });
            },
            maybeInitFocussedProposal: function () {
                if (!this.focussedProposal && this.hasSelectedProposals) {
                    this.focussedProposal = this.selectedProposals[0];
                }
            },
            maybeUnsetFocussedProposal: function (proposal, next) {
                if (this.focussedProposal && this.focussedProposal.id === proposal.id) {
                    if (next && next.id !== proposal.id) {
                        this.focussedProposal = next;
                    } else {
                        this.focussedProposal = null;
                    }
                }
            },
            maybeInitCurrentProposalImage: function () {
                if (this.currentProposalImageIndex === null) {
                    this.currentProposalImageIndex = 0;
                }
            },
            maybeInitCurrentCandidateImage: function () {
                if (this.currentCandidateImageIndex === null) {
                    this.currentCandidateImageIndex = 0;
                }
            },
            handleLoadingError: function (message) {
                messages.danger(message);
            },
            setSelectedCandidateId: function (a) {
                this.setSelectedAnnotation(a, this.selectedCandidateIds);
            },
            setCandidates: function (response) {
                CANDIDATES = response.body;

                CANDIDATES.forEach(function (p) {
                    CANDIDATES_BY_ID[p.id] = p;
                    this.setSelectedCandidateId(p);
                }, this);

                this.hasCandidates = CANDIDATES.length > 0;
            },
            fetchCandidates: function () {
                if (!this.fetchCandidatesPromise) {
                    this.startLoading();

                    this.fetchCandidatesPromise = maiaJobApi.getAnnotationCandidates({id: job.id});

                    this.fetchCandidatesPromise.then(this.setCandidates)
                        .catch(messages.handleErrorResponse)
                        .finally(this.finishLoading);
                }

                return this.fetchCandidatesPromise;
            },
            handleSelectedCandidate: function (candidate) {
                console.log('select', candidate);
            },
            setSelectedAnnotation: function (annotation, map) {
                if (annotation.selected) {
                    Vue.set(map, annotation.id, true);
                } else {
                    Vue.delete(map, annotation.id);
                }
            },
            fetchCandidateAnnotations: function (id) {
                if (!this.candidateAnnotationCache.hasOwnProperty(id)) {
                    this.candidateAnnotationCache[id] = maiaJobApi
                        .getAnnotationCandidatePoints({jobId: job.id, imageId: id})
                        .then(this.parseAnnotations);
                }

                return this.candidateAnnotationCache[id];
            },
            setCurrentCandidateImageAndAnnotations: function (args) {
                this.currentCandidateImage = args[0];
                this.currentCandidates = args[1];
                this.currentCandidatesById = {};
                this.currentCandidates.forEach(function (p) {
                    this.currentCandidatesById[p.id] = p;
                }, this);
            },
            handlePreviousCandidateImage: function () {
                this.currentCandidateImageIndex = this.previousCandidateImageIndex;
            },
            handleNextCandidateImage: function () {
                this.currentCandidateImageIndex = this.nextCandidateImageIndex;
            },
        },
        watch: {
            selectProposalsTabOpen: function (open) {
                this.visitedSelectProposalsTab = true;
                if (open) {
                    biigle.$require('keyboard').setActiveSet('select-proposals');
                }
            },
            refineProposalsTabOpen: function (open) {
                this.visitedRefineProposalsTab = true;
                if (open) {
                    biigle.$require('keyboard').setActiveSet('refine-proposals');
                    this.maybeInitFocussedProposal();
                }
            },
            selectCandidatesTabOpen: function (open) {
                this.visitedSelectCandidatesTab = true;
                if (open) {
                    biigle.$require('keyboard').setActiveSet('select-candidates');
                }
            },
            refineCandidatesTabOpen: function (open) {
                this.visitedRefineCandidatesTab = true;
                if (open) {
                    biigle.$require('keyboard').setActiveSet('refine-candidates');
                }
            },
            visitedSelectProposalsTab: function () {
                this.fetchProposals();
            },
            visitedRefineProposalsTab: function () {
                this.fetchProposals()
                    .then(this.maybeInitFocussedProposal)
                    .then(this.maybeInitCurrentProposalImage);
            },
            visitedSelectCandidatesTab: function () {
                this.fetchCandidates();
            },
            visitedRefineCandidatesTab: function () {
                this.fetchCandidates()
                    .then(this.maybeInitFocussedProposal)
                    .then(this.maybeInitCurrentCandidateImage);
            },
            currentProposalImageId: function (id) {
                if (id) {
                    this.startLoading();
                    Vue.Promise.all([
                            imagesStore.fetchAndDrawImage(id),
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
            focussedProposalToShow: function (proposal, old) {
                if (proposal) {
                    if (old && old.image_id === proposal.image_id) {
                        this.focusProposalToShow();
                    } else {
                        this.currentProposalImageIndex = this.proposalImageIds.indexOf(proposal.image_id);
                    }
                    this.setSeenProposalId(proposal);
                }
            },
            currentCandidateImageId: function (id) {
                if (id) {
                    this.startLoading();
                    Vue.Promise.all([
                            imagesStore.fetchAndDrawImage(id),
                            this.fetchCandidateAnnotations(id),
                            this.fetchCandidates(),
                        ])
                        .then(this.setCurrentCandidateImageAndAnnotations)
                        // .then(this.focusProposalToShow)
                        // .then(this.cacheNextProposalImage)
                        // .then(this.cacheNextProposalAnnotations)
                        .catch(this.handleLoadingError)
                        .finally(this.finishLoading);
                } else {
                    this.setCurrentCandidateImageAndAnnotations([null, null]);
                }
            },
        },
    });
});
