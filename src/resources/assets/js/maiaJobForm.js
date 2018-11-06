/**
 * View model for the form to submit new MAIA jobs
 */
biigle.$viewModel('maia-job-form', function (element) {
    new Vue({
        el: element,
        data: {
            showAdvanced: false,
        },
        methods: {
            toggle: function () {
                this.showAdvanced = !this.showAdvanced;
            },
        },
    });
});
