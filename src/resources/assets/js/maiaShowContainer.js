/**
 * View model for the main view of a MAIA job
 */
biigle.$viewModel('maia-show-container', function (element) {
    var job = biigle.$require('maia.job');
    var states = biigle.$require('maia.states');
    var maiaJobApi = biigle.$require('maia.api.maiaJob');
    var maiaAnnotationApi = biigle.$require('maia.api.maiaAnnotation');
    var messages = biigle.$require('messages.store');
    var imagesStore = biigle.$require('annotations.stores.images');

    // We have to take very great care from preventing Vue to make the training proposals
    // fully reactive. This can be a huge array and Vue is not fast enough to ensure a
    // fluid UX if it is fully reactive.
    var trainingProposals = [];
    var trainingProposalsById = {};

    new Vue({
        el: element,
        mixins: [biigle.$require('core.mixins.loader')],
        components: {
            sidebar: biigle.$require('annotations.components.sidebar'),
            sidebarTab: biigle.$require('core.components.sidebarTab'),
            selectTpTab: biigle.$require('maia.components.selectTpTab'),
            tpImageGrid: biigle.$require('maia.components.tpImageGrid'),
            refineTpTab: biigle.$require('maia.components.refineTpTab'),
            refineTpCanvas: biigle.$require('maia.components.refineTpCanvas'),
            reviewAcTab: biigle.$require('maia.components.reviewAcTab'),
            acImageGrid: biigle.$require('maia.components.acImageGrid'),
        },
        data: {
            visitedSelectTpTab: false,
            visitedRefineTpTab: false,
            visitedReviewAcTab: false,
            openTab: 'info',
            hasTrainingProposals: false,
            // Track these manually and not via a computed property because the number of
            // training proposals can be huge.
            selectedTrainingProposalIds: {},
            lastSelectedTp: null,
            currentImage: null,
            currentTpIndex: 0,
            annotationCandidates: [],
        },
        computed: {
            infoTabOpen: function () {
                return this.openTab === 'info';
            },
            selectTpTabOpen: function () {
                return this.openTab === 'select-training-proposals';
            },
            refineTpTabOpen: function () {
                return this.openTab === 'refine-training-proposals';
            },
            reviewAcTabOpen: function () {
                return this.openTab === 'review-annotation-candidates';
            },
            isInTrainingProposalState: function () {
                return job.state_id === states['training-proposals'];
            },
            isInAnnotationCandidateState: function () {
                return job.state_id === states['annotation-candidates'];
            },
            trainingProposals: function () {
                if (this.hasTrainingProposals) {
                    return trainingProposals;
                }

                return [];
            },
            selectedTrainingProposals: function () {
                return Object.keys(this.selectedTrainingProposalIds)
                    .map(function (id) {
                        return trainingProposalsById[id];
                    });
            },
            // hasAnnotationCandidates: function () {
            //     return this.annotationCandidates.length > 0;
            // },
            hasNoSelectedTp: function () {
                return this.selectedTrainingProposals.length === 0;
            },
            visitedSelectOrRefineTpTab: function () {
                return this.visitedSelectTpTab || this.visitedRefineTpTab;
            },
        },
        methods: {
            handleSidebarToggle: function () {
                this.$nextTick(function () {
                    this.$refs.imageGrid.$emit('resize');
                });
            },
            handleTabOpened: function (tab) {
                this.openTab = tab;
            },
            setTrainingProposals: function (response) {
                var self = this;
                trainingProposals = response.body;
                trainingProposals.forEach(function (p) {
                    trainingProposalsById[p.id] = p;
                    self.setSelectedTrainingProposalId(p);
                });

                this.hasTrainingProposals = trainingProposals.length > 0;
            },
            fetchTrainingProposals: function () {
                this.startLoading();

                return maiaJobApi.getTrainingProposals({id: job.id})
                    .then(this.setTrainingProposals)
                    .catch(messages.handleErrorResponse)
                    .finally(this.finishLoading);
            },
            openRefineTpTab: function () {
                this.openTab = 'refine-training-proposals';
            },
            handleSelectedTrainingProposal: function (proposal, event) {
                if (proposal.selected) {
                    this.updateSelectTrainingProposal(proposal, false);
                } else {
                    if (event.shiftKey && this.lastSelectedTp) {
                        this.selectAllTpBetween(proposal, this.lastSelectedTp);
                    } else {
                        this.lastSelectedTp = proposal;
                        this.updateSelectTrainingProposal(proposal, true);
                    }
                }
            },
            selectAllTpBetween: function (first, second) {
                var index1 = this.trainingProposals.indexOf(first);
                var index2 = this.trainingProposals.indexOf(second);
                if (index2 < index1) {
                    var tmp = index2;
                    index2 = index1;
                    index1 = tmp;
                }

                for (var i = index1 + 1; i <= index2; i++) {
                    this.updateSelectTrainingProposal(this.trainingProposals[i], true);
                }
            },
            updateSelectTrainingProposal: function (proposal, selected) {
                proposal.selected = selected;
                this.setSelectedTrainingProposalId(proposal);
                maiaAnnotationApi.update({id: proposal.id}, {selected: selected})
                    .catch(function (response) {
                        messages.handleErrorResponse(response);
                        proposal.selected = !selected;
                        this.setSelectedTrainingProposalId(proposal);
                    });
            },
            setSelectedTrainingProposalId: function (p) {
                if (p.selected) {
                    Vue.set(this.selectedTrainingProposalIds, p.id, true);
                } else {
                    Vue.delete(this.selectedTrainingProposalIds, p.id);
                }
            },
            fetchCurrentImage: function () {
                this.startLoading();

                return imagesStore.fetchAndDrawImage(this.currentImageId)
                    .catch(function (message) {
                        messages.danger(message);
                    })
                    .then(this.setCurrentImage)
                    .then(this.cacheNextImage)
                    .finally(this.finishLoading);
            },
            setCurrentImage: function (image) {
                this.currentImage = image;
            },
            cacheNextImage: function () {
                // Do nothing if there is only one image.
                if (this.currentImageId !== this.nextImageId) {
                    imagesStore.fetchImage(this.nextImageId)
                        // Ignore errors in this case. The application will try to reload
                        // the data again if the user switches to the respective image
                        // and display the error message then.
                        .catch(function () {});
                }
            },
            handleNext: function () {
                if (this.currentTp) {
                    this.currentTp.seen = true;
                }

                this.currentTpIndex = this.nextTpIndex;
            },
            handlePrevious: function () {
                if (this.currentTp) {
                    this.currentTp.seen = true;
                }

                this.currentTpIndex = this.previousTpIndex;
            },
            handleRefineTp: function (proposals) {
                Vue.Promise.all(proposals.map(this.updateTpPoints))
                    .catch(messages.handleErrorResponse);
            },
            updateTpPoints: function (proposal) {
                var self = this;

                return maiaAnnotationApi
                    .update({id: proposal.id}, {points: proposal.points})
                    .then(function () {
                        self.tpById[proposal.id].points = proposal.points;
                    });
            },
            focusCurrentTp: function () {
                this.$refs.refineCanvas.focusAnnotation(this.currentTp, true, false);
            },
            handleSelectTp: function (proposal) {
                this.updateSelectTrainingProposal(proposal, true);
            },
            handleUnselectTp: function (proposal) {
                this.updateSelectTrainingProposal(proposal, false);
            },
            // setAnnotationCandidates: function (response) {
            //     this.annotationCandidates = response.body.map(function (c) {
            //         c.shape = 'Circle';
            //         return c;
            //     });
            // },
            // fetchAnnotationCandidates: function () {
            //     this.startLoading();

            //     return maiaJobApi.getAnnotationCandidates({id: job.id})
            //         .then(this.setAnnotationCandidates)
            //         .catch(messages.handleErrorResponse)
            //         .finally(this.finishLoading);
            // },
            // handleSelectedAnnotationCandidate: function (candidate) {
            //     console.log('select', candidate);
            // },
        },
        watch: {
            selectTpTabOpen: function (open) {
                this.visitedSelectTpTab = true;
                if (open) {
                    biigle.$require('keyboard').setActiveSet('select-tp');
                }
            },
            refineTpTabOpen: function (open) {
                this.visitedRefineTpTab = true;
                if (open) {
                    biigle.$require('keyboard').setActiveSet('refine-tp');
                }
            },
            reviewAcTabOpen: function (opens) {
                this.visitedReviewAcTab = true;
                if (open) {
                    biigle.$require('keyboard').setActiveSet('review-ac');
                }
            },
            visitedSelectOrRefineTpTab: function () {
                this.fetchTrainingProposals();
            },
            visitedReviewAcTab: function () {
                this.fetchAnnotationCandidates();
            },
            currentImageId: function (id) {
                if (id) {
                    this.fetchCurrentImage().then(this.focusCurrentTp);
                } else {
                    this.setCurrentImage(null);
                }
            },
            currentTp: function (tp) {
                if (tp) {
                    this.focusCurrentTp();
                }
            },
        },
    });
});
