<script>
import knowledgeTransferVolumeApi from './api/knowledgeTransferVolume';
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
            knowledgeTransferVolumes: [],
            knowledgeTransferVolume: null,
            shouldFetchKnowledgeTransferVolumes: false,
            knowledgeTransferTypeaheadTemplate: '{{item.name}}<br><small>({{item.description}})</small>'
        };
    },
    computed: {
        hasLabels() {
            return this.labels.length > 0;
        },
        hasSelectedLabels() {
            return this.selectedLabels.length > 0;
        },
        useExistingAnnotations() {
            return this.trainingDataMethod === 'own_annotations';
        },
        useNoveltyDetection() {
            return this.trainingDataMethod === 'novelty_detection';
        },
        useKnowledgeTransfer() {
            return this.trainingDataMethod === 'knowledge_transfer';
        },
        canSubmit() {
            return this.submitted || (this.useKnowledgeTransfer && !this.knowledgeTransferVolume);
        },
        hasNoKnowledgeTransferVolumes() {
            return this.shouldFetchKnowledgeTransferVolumes && !this.loading && this.knowledgeTransferVolumes.length === 0;
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
        handleSelectedKnowledgeTransferVolume(volume) {
            this.knowledgeTransferVolume = volume;
        },
        setKnowledgeTransferVolumes(response) {
            this.knowledgeTransferVolumes = response.body
                .filter(volume => volume.id !== this.volumeId)
                .map(function (volume) {
                    volume.description = volume.projects
                        .map(project => project.name)
                        .join(', ');

                    return volume;
                });
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
        useKnowledgeTransfer(use) {
            if (use) {
                this.shouldFetchKnowledgeTransferVolumes = true;
            }
        },
        shouldFetchKnowledgeTransferVolumes(fetch) {
            if (fetch) {
                this.startLoading();
                knowledgeTransferVolumeApi.get()
                    .then(this.setKnowledgeTransferVolumes, handleErrorResponse)
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