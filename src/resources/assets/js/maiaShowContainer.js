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
            fetchTrainingProposalPromise: null,
            hasTrainingProposals: false,
            // Track these manually and not via a computed property because the number of
            // training proposals can be huge.
            selectedTrainingProposalIds: {},
            seenTrainingProposalIds: {},
            lastSelectedTrainingProposal: null,
            currentImage: null,
            currentImageIndex: null,
            currentTrainingProposals: [],
            currentTrainingProposalsById: {},
            focussedTrainingProposal: null,
            tpAnnotationCache: {},
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
            hasSelectedTrainingProposals: function () {
                return this.selectedTrainingProposals.length > 0;
            },
            imageIds: function () {
                var tmp = {};
                this.trainingProposals.forEach(function (p) {
                    tmp[p.image_id] = undefined;
                });

                return Object.keys(tmp).map(function (id) {
                    return parseInt(id, 10);
                });
            },
            currentImageId: function () {
                return this.imageIds[this.currentImageIndex];
            },
            nextImageIndex: function () {
                return (this.currentImageIndex + 1) % this.imageIds.length;
            },
            nextImageId: function () {
                return this.imageIds[this.nextImageIndex];
            },
            nextFocussedImageId: function () {
                if (this.nextFocussedTrainingProposal) {
                    return this.nextFocussedTrainingProposal.image_id;
                }

                return this.nextImageId;
            },
            previousImageIndex: function () {
              return (this.currentImageIndex - 1 + this.imageIds.length) % this.imageIds.length;
            },
            previousImageId: function () {
                return this.imageIds[this.previousImageIndex];
            },
            hasCurrentImage: function () {
                return this.currentImage !== null;
            },
            currentSelectedTrainingProposals: function () {
                return this.currentTrainingProposals.filter(function (p) {
                    return this.selectedTrainingProposalIds.hasOwnProperty(p.id);
                }, this);
            },
            currentUnselectedTrainingProposals: function () {
                return this.currentTrainingProposals.filter(function (p) {
                    return !this.selectedTrainingProposalIds.hasOwnProperty(p.id);
                }, this);
            },
            previousFocussedTrainingProposal: function () {
                var index = (this.selectedTrainingProposals.indexOf(this.focussedTrainingProposal) - 1 + this.selectedTrainingProposals.length) % this.selectedTrainingProposals.length;

                return this.selectedTrainingProposals[index];
            },
            nextFocussedTrainingProposal: function () {
                var index = (this.selectedTrainingProposals.indexOf(this.focussedTrainingProposal) + 1) % this.selectedTrainingProposals.length;

                return this.selectedTrainingProposals[index];
            },
            // The focussed training proposal might change while the refine tab tool is
            // closed but it should be updated only when it it opened again. Else the
            // canvas would not update correctly.
            focussedTrainingProposalToShow: function () {
                if (this.refineTpTabOpen) {
                    return this.focussedTrainingProposal;
                }

                return null;
            },
            focussedTrainingProposalArray: function () {
                if (this.focussedTrainingProposalToShow) {
                    return this.currentSelectedTrainingProposals.filter(function (p) {
                        return p.id === this.focussedTrainingProposalToShow.id;
                    }, this);
                }

                return [];
            },
            selectedAndSeenTrainingProposals: function () {
                return this.selectedTrainingProposals.filter(function (p) {
                    return this.seenTrainingProposalIds.hasOwnProperty(p.id);
                }, this);
            },
            // hasAnnotationCandidates: function () {
            //     return this.annotationCandidates.length > 0;
            // },
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
                trainingProposals = response.body;

                trainingProposals.forEach(function (p) {
                    trainingProposalsById[p.id] = p;
                    this.setSelectedTrainingProposalId(p);
                }, this);

                this.hasTrainingProposals = trainingProposals.length > 0;
            },
            fetchTrainingProposals: function () {
                if (!this.fetchTrainingProposalPromise) {
                    this.startLoading();

                    this.fetchTrainingProposalPromise = maiaJobApi.getTrainingProposals({id: job.id});

                    this.fetchTrainingProposalPromise.then(this.setTrainingProposals)
                        .catch(messages.handleErrorResponse)
                        .finally(this.finishLoading);
                }

                return this.fetchTrainingProposalPromise;
            },
            openRefineTpTab: function () {
                this.openTab = 'refine-training-proposals';
            },
            updateSelectTrainingProposal: function (proposal, selected) {
                proposal.selected = selected;
                this.setSelectedTrainingProposalId(proposal);
                var promise = maiaAnnotationApi.update({id: proposal.id}, {selected: selected});

                promise.catch(function (response) {
                    messages.handleErrorResponse(response);
                    proposal.selected = !selected;
                    this.setSelectedTrainingProposalId(proposal);
                });

                return promise;
            },
            setSelectedTrainingProposalId: function (p) {
                if (p.selected) {
                    Vue.set(this.selectedTrainingProposalIds, p.id, true);
                } else {
                    Vue.delete(this.selectedTrainingProposalIds, p.id);
                }
            },
            setSeenTrainingProposalId: function (p) {
                Vue.set(this.seenTrainingProposalIds, p.id, true);
            },
            fetchTpAnnotations: function (id) {
                if (!this.tpAnnotationCache.hasOwnProperty(id)) {
                    this.tpAnnotationCache[id] = maiaJobApi
                        .getTrainingProposalPoints({jobId: job.id, imageId: id})
                        .then(this.parseTpAnnotations);
                }

                return this.tpAnnotationCache[id];
            },
            parseTpAnnotations: function (response) {
                return Object.keys(response.body).map(function (id) {
                    return {
                        id: parseInt(id, 10),
                        shape: 'Circle',
                        points: response.body[id],
                    };
                });
            },
            setCurrentImageAndTpAnnotations: function (args) {
                this.currentImage = args[0];
                this.currentTrainingProposals = args[1];
                this.currentTrainingProposalsById = {};
                this.currentTrainingProposals.forEach(function (p) {
                    this.currentTrainingProposalsById[p.id] = p;
                }, this);
            },
            cacheNextImage: function () {
                // Do nothing if there is only one image.
                if (this.currentImageId !== this.nextFocussedImageId) {
                    imagesStore.fetchImage(this.nextFocussedImageId)
                        // Ignore errors in this case. The application will try to reload
                        // the data again if the user switches to the respective image
                        // and display the error message then.
                        .catch(function () {});
                }
            },
            cacheNextTpAnnotations: function () {
                // Do nothing if there is only one image.
                if (this.currentImageId !== this.nextFocussedImageId) {
                    this.fetchTpAnnotations(this.nextFocussedImageId)
                        // Ignore errors in this case. The application will try to reload
                        // the data again if the user switches to the respective image
                        // and display the error message then.
                        .catch(function () {});
                }
            },
            handlePreviousImage: function () {
                this.currentImageIndex = this.previousImageIndex;
            },
            handlePrevious: function () {
                if (this.previousFocussedTrainingProposal) {
                    this.focussedTrainingProposal = this.previousFocussedTrainingProposal;
                }
            },
            handleNextImage: function () {
                this.currentImageIndex = this.nextImageIndex;
            },
            handleNext: function () {
                if (this.nextFocussedTrainingProposal) {
                    this.focussedTrainingProposal = this.nextFocussedTrainingProposal;
                }
            },
            handleRefineTp: function (proposals) {
                Vue.Promise.all(proposals.map(this.updateTpPoints))
                    .catch(messages.handleErrorResponse);
            },
            updateTpPoints: function (proposal) {
                var toUpdate = this.currentTrainingProposalsById[proposal.id];
                return maiaAnnotationApi
                    .update({id: proposal.id}, {points: proposal.points})
                    .then(function () {
                        toUpdate.points = proposal.points;
                    });
            },
            focusTrainingProposalToShow: function () {
                if (this.focussedTrainingProposalToShow) {
                    var p = this.currentTrainingProposalsById[this.focussedTrainingProposalToShow.id];
                    if (p) {
                        this.$refs.refineCanvas.focusAnnotation(p, true, false);
                    }
                }
            },
            handleSelectedTrainingProposal: function (proposal, event) {
                if (proposal.selected) {
                    this.unselectTrainingProposal(proposal);
                } else {
                    if (event.shiftKey && this.lastSelectedTrainingProposal) {
                        this.selectAllTpBetween(proposal, this.lastSelectedTrainingProposal);
                    } else {
                        this.lastSelectedTrainingProposal = proposal;
                        this.selectTrainingProposal(proposal);
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
                    this.selectTrainingProposal(this.trainingProposals[i]);
                }
            },
            selectTrainingProposal: function (proposal) {
                this.updateSelectTrainingProposal(proposal, true)
                    .then(this.maybeInitFocussedTrainingProposal);
            },
            unselectTrainingProposal: function (proposal) {
                var next = this.nextFocussedTrainingProposal;
                this.updateSelectTrainingProposal(proposal, false)
                    .bind(this)
                    .then(function () {
                        this.maybeUnsetFocussedTrainingProposal(proposal, next);
                    });
            },
            maybeInitFocussedTrainingProposal: function () {
                if (!this.focussedTrainingProposal && this.hasSelectedTrainingProposals) {
                    this.focussedTrainingProposal = this.selectedTrainingProposals[0];
                }
            },
            maybeUnsetFocussedTrainingProposal: function (proposal, next) {
                if (this.focussedTrainingProposal && this.focussedTrainingProposal.id === proposal.id) {
                    if (next && next.id !== proposal.id) {
                        this.focussedTrainingProposal = next;
                    } else {
                        this.focussedTrainingProposal = null;
                    }
                }
            },
            maybeInitCurrentImage: function () {
                if (this.currentImageIndex === null) {
                    this.currentImageIndex = 0;
                }
            },
            handleLoadingError: function (message) {
                messages.danger(message);
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
                    this.maybeInitFocussedTrainingProposal();
                }
            },
            reviewAcTabOpen: function (open) {
                this.visitedReviewAcTab = true;
                if (open) {
                    biigle.$require('keyboard').setActiveSet('review-ac');
                }
            },
            visitedSelectTpTab: function () {
                this.fetchTrainingProposals();
            },
            visitedRefineTpTab: function () {
                this.fetchTrainingProposals()
                    .then(this.maybeInitFocussedTrainingProposal)
                    .then(this.maybeInitCurrentImage);
            },
            visitedReviewAcTab: function () {
                this.fetchAnnotationCandidates();
            },
            currentImageId: function (id) {
                if (id) {
                    this.startLoading();
                    Vue.Promise.all([
                            imagesStore.fetchAndDrawImage(id),
                            this.fetchTpAnnotations(id),
                            // This promise is created when the refine tab is first
                            // opened and needs to be resolved so we know which TP are
                            // selected and which not.
                            this.fetchTrainingProposalPromise,
                        ])
                        .then(this.setCurrentImageAndTpAnnotations)
                        .then(this.focusTrainingProposalToShow)
                        .then(this.cacheNextImage)
                        .then(this.cacheNextTpAnnotations)
                        .catch(this.handleLoadingError)
                        .finally(this.finishLoading);
                } else {
                    this.setCurrentImageAndTpAnnotations([null, null]);
                }
            },
            focussedTrainingProposalToShow: function (p) {
                if (p) {
                    this.currentImageIndex = this.imageIds.indexOf(p.image_id);
                    this.setSeenTrainingProposalId(p);
                }
            },
        },
    });
});
