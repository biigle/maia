import {handleErrorResponse} from './import';
import {LabelTypeahead} from './import';
import {LoaderMixin} from './import';

/**
 * View model for the form to submit new MAIA jobs
 */
export default {
    mixins: [LoaderMixin],
    components: {
        typeahead: LabelTypeahead,
    },
    data: {
        volumeId: null,
        useExistingAnnotations: false,
        skipNoveltyDetection: false,
        showAdvanced: false,
        shouldFetchLabels: false,
        labels: [],
        selectedLabels: [],
        submitted: false,
    },
    computed: {
        canSkipNoveltyDetection() {
            return this.useExistingAnnotations && this.hasLabels;
        },
        hasLabels() {
            return this.labels.length > 0;
        },
        hasSelectedLabels() {
            return this.selectedLabels.length > 0;
        },
        showRestrictLabelsInput() {
            return this.showAdvanced && this.useExistingAnnotations && this.hasLabels;
        },
        hasNoExistingAnnotations() {
            return this.useExistingAnnotations && !this.hasLabels && !this.loading;
        },
    },
    methods: {
        toggle() {
            this.showAdvanced = !this.showAdvanced;
        },
        setLabels(response) {
            this.labels = response.body;
        },
        handleSelectedLabel(label) {
            if (this.selectedLabels.indexOf(label) === -1) {
                this.selectedLabels.push(label);
            }
        },
        handleUnselectLabel(label) {
            let index = this.selectedLabels.indexOf(label);
            if (index >= 0) {
                this.selectedLabels.splice(index, 1);
            }
        },
        submit() {
            this.submitted = true;
        },
    },
    watch: {
        useExistingAnnotations(use) {
            if (use) {
                this.shouldFetchLabels = true;
            }
        },
        shouldFetchLabels(fetch) {
            if (fetch) {
                this.startLoading();
                this.$http.get('api/v1/volumes{/id}/annotation-labels', {params: {id: this.volumeId}})
                    .then(this.setLabels, handleErrorResponse)
                    .finally(this.finishLoading);
            }
        },
    },
    created() {
        this.volumeId = biigle.$require('maia.volumeId');
        this.useExistingAnnotations = biigle.$require('maia.useExistingAnnotations');
        this.skipNoveltyDetection = biigle.$require('maia.skipNoveltyDetection');

        if (this.useExistingAnnotations) {
            this.shouldFetchLabels = true;
        }
    },
};
