<script>
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
    data() {
        return {
            volumeId: null,
            showAdvanced: false,
            shouldFetchLabels: false,
            labels: [],
            selectedLabels: [],
            submitted: false,
            trainScheme: [],
            trainingDataMethod: '',
        };
    },
    computed: {
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
        useExistingAnnotations() {
            return this.trainingDataMethod === 'own_annotations';
        },
        useNoveltyDetection() {
            return this.trainingDataMethod === 'novelty_detection';
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
        removeTrainStep(index) {
            this.trainScheme.splice(index, 1);
        },
        addTrainStep() {
            let step = {layers: 'heads', epochs: 10, learning_rate: 0.001};
            if (this.trainScheme.length > 0) {
                let last = this.trainScheme[this.trainScheme.length - 1];
                step.layers = last.layers;
                step.epochs = last.epochs;
                step.learning_rate = last.learning_rate;
            }
            this.trainScheme.push(step);
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
        this.trainScheme = biigle.$require('maia.trainScheme');
        this.showAdvanced = biigle.$require('maia.hasErrors');
        this.trainingDataMethod = biigle.$require('maia.trainingDataMethod');

        if (this.useExistingAnnotations) {
            this.shouldFetchLabels = true;
        }
    },
};
</script>
