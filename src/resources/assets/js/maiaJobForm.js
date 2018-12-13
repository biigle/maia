/**
 * View model for the form to submit new MAIA jobs
 */
biigle.$viewModel('maia-job-form', function (element) {
    var volumeId = biigle.$require('maia.volumeId');

    new Vue({
        el: element,
        mixins: [biigle.$require('core.mixins.loader')],
        components: {
            typeahead: biigle.$require('labelTrees.components.labelTypeahead'),
        },
        data: {
            useExistingAnnotations: biigle.$require('maia.useExistingAnnotations'),
            skipNoveltyDetection: biigle.$require('maia.skipNoveltyDetection'),
            showAdvanced: false,
            shouldFetchLabels: false,
            labels: [],
            selectedLabels: [],
            submitted: false,
        },
        computed: {
            canSkipNoveltyDetection: function () {
                return this.useExistingAnnotations && this.hasLabels;
            },
            hasLabels: function () {
                return this.labels.length > 0;
            },
            hasSelectedLabels: function () {
                return this.selectedLabels.length > 0;
            },
            showRestrictLabelsInput: function () {
                return this.showAdvanced && this.useExistingAnnotations && this.hasLabels;
            },
            hasNoExistingAnnotations: function () {
                return this.useExistingAnnotations && !this.hasLabels && !this.loading;
            },
        },
        methods: {
            toggle: function () {
                this.showAdvanced = !this.showAdvanced;
            },
            setLabels: function (response) {
                this.labels = response.body;
            },
            handleSelectedLabel: function (label) {
                if (this.selectedLabels.indexOf(label) === -1) {
                    this.selectedLabels.push(label);
                }
            },
            handleUnselectLabel: function (label) {
                var index = this.selectedLabels.indexOf(label);
                if (index >= 0) {
                    this.selectedLabels.splice(index, 1);
                }
            },
            submit: function () {
                this.submitted = true;
            },
        },
        watch: {
            useExistingAnnotations: function (use) {
                if (use) {
                    this.shouldFetchLabels = true;
                }
            },
            shouldFetchLabels: function (fetch) {
                if (fetch) {
                    this.startLoading();
                    this.$http.get('api/v1/volumes{/id}/annotation-labels', {params: {id: volumeId}})
                        .then(this.setLabels, biigle.$require('messages.store').handleErrorResponse)
                        .finally(this.finishLoading);
                }
            },
        },
    });
});
