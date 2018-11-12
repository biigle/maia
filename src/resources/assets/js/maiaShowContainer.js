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
        },
        data: {
            visitedSelectTpTab: false,
            visitedRefineTpTab: false,
            visitedReviewAcTab: false,
            openTab: 'info',
            trainingProposalsPromise: null,
            trainingProposals: [],
            selectTpOffset: 0,
            lastSelectedTp: null,
            firstImageLoaded: false,
            currentImage: null,
            currentTpIndex: 0,
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
            hasNoTrainingProposals: function () {
                return !this.loading && this.trainingProposals.length === 0;
            },
            isInTrainingProposalState: function () {
                return job.state_id === states['training-proposals'];
            },
            imageIds: function () {
                var tmp = {};

                return this.trainingProposals
                    .map(function (p) {
                        return p.image_id;
                    })
                    .filter(function (id) {
                        return tmp.hasOwnProperty(id) ? false : (tmp[id] = true);
                    });
            },
            tpOrderedByImageId: function () {
                return this.trainingProposals.slice().sort(function (a, b) {
                    return a.image_id - b.image_id;
                });
            },
            selectedTpOrderedByImageId: function () {
                return this.tpOrderedByImageId.filter(function (p) {
                    return p.selected;
                });
            },
            previousTpIndex: function () {
                return (this.currentTpIndex + this.selectedTpOrderedByImageId.length - 1) % this.selectedTpOrderedByImageId.length;
            },
            nextTpIndex: function () {
                return (this.currentTpIndex + 1) % this.selectedTpOrderedByImageId.length;
            },
            currentTp: function () {
                if (this.selectedTpOrderedByImageId.length > 0) {
                    return this.selectedTpOrderedByImageId[this.currentTpIndex];
                }

                return null;
            },
            currentImageIdsIndex: function () {
                return this.imageIds.indexOf(this.currentImageId);
            },
            currentImageId: function () {
                if (this.currentTp) {
                    return this.currentTp.image_id;
                }

                return null;
            },
            previousImageIdsIndex: function () {
                return (this.currentImageIdsIndex + this.imageIds.length - 1) % this.imageIds.length;
            },
            previousImageId: function () {
                return this.imageIds[this.nextImageIdsIndex];
            },
            nextImageIdsIndex: function () {
                return (this.currentImageIdsIndex + 1) % this.imageIds.length;
            },
            nextImageId: function () {
                return this.imageIds[this.nextImageIdsIndex];
            },
            tpForCurrentImage: function () {
                // The annotations can only be properly drawn if the image is set.
                if (this.firstImageLoaded) {
                    return this.trainingProposals.filter(function (p) {
                        return p.image_id === this.currentImageId;
                    }, this);
                }

                return [];
            },
            selectedTpForCurrentImage: function () {
                return this.tpForCurrentImage.filter(function (p) {
                    return p.selected;
                });
            },
            currentTpArray: function () {
                if (this.firstImageLoaded && this.currentTp) {
                    return [this.currentTp];
                }

                return [];
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
                this.trainingProposals = response.body.map(function (p) {
                    p.shape = 'Circle';
                    return p;
                });
            },
            fetchTrainingProposals: function () {
                if (!this.trainingProposalsPromise) {
                    this.startLoading();
                    this.trainingProposalsPromise =
                        maiaJobApi.getTrainingProposals({id: job.id})
                            .then(this.setTrainingProposals)
                            .catch(messages.handleErrorResponse)
                            .finally(this.finishLoading);
                }

                return this.trainingProposalsPromise;
            },
            openRefineTpTab: function () {
                this.openTab = 'refine-training-proposals';
            },
            handleSelectedTrainingProposal: function (proposal, event) {
                if (proposal.selected) {
                    proposal.selected = false;
                    maiaAnnotationApi.update({id: proposal.id}, {selected: false})
                        .catch(function (response) {
                            messages.handleErrorResponse(response);
                            proposal.selected = true;
                        });
                } else {
                    if (event.shiftKey && this.lastSelectedTp) {
                        this.selectAllTpBetween(proposal, this.lastSelectedTp);
                    } else {
                        this.lastSelectedTp = proposal;
                        this.storeSelectTrainingProposal(proposal);
                    }
                }
            },
            updateSelectTpOffset: function (offset) {
                this.selectTpOffset = offset;
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
                    this.storeSelectTrainingProposal(this.trainingProposals[i]);
                }
            },
            storeSelectTrainingProposal: function (proposal) {
                proposal.selected = true;
                maiaAnnotationApi.update({id: proposal.id}, {selected: true})
                    .catch(function (response) {
                        messages.handleErrorResponse(response);
                        proposal.selected = false;
                    });
            },
            startInstanceSegmentation: function () {
                console.log('start instance segmentation');
            },
            fetchCurrentImage: function () {
                this.startLoading();

                return imagesStore.fetchAndDrawImage(this.currentImageId)
                    .catch(function (message) {
                        messages.danger(message);
                    })
                    .then(this.updateCurrentImage)
                    .then(this.cacheNextImage)
                    .finally(this.finishLoading);
            },
            updateCurrentImage: function (image) {
                this.firstImageLoaded = true;
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
                this.currentTpIndex = this.nextTpIndex;
            },
            handlePrevious: function () {
                this.currentTpIndex = this.previousTpIndex;
            },
        },
        watch: {
            selectTpTabOpen: function () {
                this.visitedSelectTpTab = true;
            },
            refineTpTabOpen: function () {
                this.visitedRefineTpTab = true;
            },
            reviewAcTabOpen: function () {
                this.visitedReviewAcTab = true;
            },
            visitedSelectTpTab: function () {
                this.fetchTrainingProposals();
            },
            visitedRefineTpTab: function () {
                this.fetchTrainingProposals().then(this.fetchCurrentImage);
            },
            currentImageId: function () {
                this.fetchCurrentImage();
            },
        },
    });
});
