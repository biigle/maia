/**
 * View model for the main view of a MAIA job
 */
biigle.$viewModel('maia-show-container', function (element) {
    var job = biigle.$require('maia.job');
    var states = biigle.$require('maia.states');
    var maiaJobApi = biigle.$require('maia.api.maiaJob');
    var maiaAnnotationApi = biigle.$require('maia.api.maiaAnnotation');

    new Vue({
        el: element,
        mixins: [biigle.$require('core.mixins.loader')],
        components: {
            sidebar: biigle.$require('annotations.components.sidebar'),
            sidebarTab: biigle.$require('core.components.sidebarTab'),
            filterTpTab: biigle.$require('maia.components.filterTpTab'),
            tpImageGrid: biigle.$require('maia.components.tpImageGrid'),
        },
        data: {
            visitedFilterTpTab: false,
            visitedRefineTpTab: false,
            visitedFilterAcTab: false,
            openTab: 'info',
            trainingProposals: [],
            filterTpOffset: 0,
        },
        computed: {
            shouldVisitTpTab: function () {
                return !this.visitedFilterTpTab && job.state_id === states['training-proposals'];
            },
            shouldVisitAcTab: function () {
                return !this.visitedFilterAcTab && job.state_id === states['annotation-candidates'];
            },
            tpTabHighlight: function () {
                return this.shouldVisitTpTab ? 'warning' : false;
            },
            acTabHighlight: function () {
                return this.shouldVisitAcTab ? 'warning' : false;
            },
            infoTabOpen: function () {
                return this.openTab === 'info';
            },
            filterTpTabOpen: function () {
                return this.openTab === 'filter-training-proposals';
            },
            refineTpTabOpen: function () {
                return this.openTab === 'refine-training-proposals';
            },
            filterAcTabOpen: function () {
                return this.openTab === 'filter-annotation-candidates';
            },
            visitedFilterOrRefineTpTab: function () {
                return this.visitedFilterTpTab || this.visitedRefineTpTab;
            },
            hasNoTrainingProposals: function () {
                return this.trainingProposals.length === 0;
            },
        },
        methods: {
            handleTabOpened: function (tab) {
                this.openTab = tab;
            },
            setTrainingProposals: function (response) {
                this.trainingProposals = response.body;
            },
            fetchTrainingProposals: function () {
                this.startLoading();
                // TODO handle error
                maiaJobApi.getTrainingProposals({id: job.id})
                    .then(this.setTrainingProposals)
                    .finally(this.finishLoading);
            },
            openRefineTpTab: function () {
                this.openTab = 'refine-training-proposals';
            },
            handleSelectedTrainingProposal: function (proposal) {
                proposal.selected = true;
                maiaAnnotationApi.update({id: proposal.id}, {selected: true})
                    .catch(function () {
                        // TODO show error message
                        proposal.selected = false;
                    });
            },
            handleDeselectedTrainingProposal: function (proposal) {
                proposal.selected = false;
                maiaAnnotationApi.update({id: proposal.id}, {selected: false})
                    .catch(function () {
                        // TODO show error message
                        proposal.selected = true;
                    });
            },
            updateFilterTpOffset: function (offset) {
                this.filterTpOffset = offset;
            },
        },
        watch: {
            filterTpTabOpen: function () {
                this.visitedFilterTpTab = true;
            },
            refineTpTabOpen: function () {
                this.visitedRefineTpTab = true;
            },
            filterAcTabOpen: function () {
                this.visitedFilterAcTab = true;
            },
            visitedFilterOrRefineTpTab: function () {
                this.fetchTrainingProposals();
            },
        },
    });
});
