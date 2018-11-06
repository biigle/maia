/**
 * View model for the main view of a MAIA job
 */
biigle.$viewModel('maia-show-container', function (element) {
    var job = biigle.$require('maia.job');
    var states = biigle.$require('maia.states');

    new Vue({
        el: element,
        components: {
            sidebar: biigle.$require('annotations.components.sidebar'),
            sidebarTab: biigle.$require('core.components.sidebarTab'),
        },
        data: {
            visitedFilterTpTab: false,
            visitedFilterAcTab: false,
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
        },
        methods: {
            handleTabOpened: function (tab) {
                if (tab === 'filter-training-proposals') {
                    this.visitedFilterTpTab = true;
                } else if (tab === 'filter-annotation-candidates') {
                    this.visitedFilterAcTab = true;
                }
            },
        },
    });
});
