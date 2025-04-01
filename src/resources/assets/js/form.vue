<script>
import areaKnowledgeTransferVolumeApi from './api/areaKnowledgeTransferVolume.js';
import knowledgeTransferVolumeApi from './api/knowledgeTransferVolume.js';
import {handleErrorResponse} from './import.js';
import {LabelTypeahead} from './import.js';
import {LoaderMixin} from './import.js';
import {VolumesApi} from './import.js';

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
            trainingDataMethod: '',
            distanceKnowledgeTransferVolumes: [],
            areaKnowledgeTransferVolumes: [],
            knowledgeTransferVolume: null,
            shouldFetchAreaKnowledgeTransferVolumes: false,
            shouldFetchDistanceKnowledgeTransferVolumes: false,
            knowledgeTransferLabelCache: [],
            selectedKnowledgeTransferLabels: [],
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
            return this.trainingDataMethod === 'knowledge_transfer' || this.trainingDataMethod === 'area_knowledge_transfer';
        },
        canSubmit() {
            return this.submitted || (this.useKnowledgeTransfer && !this.knowledgeTransferVolume);
        },
        knowledgeTransferVolumes() {
            if (this.trainingDataMethod === 'area_knowledge_transfer') {
                return this.areaKnowledgeTransferVolumes;
            }

            return this.distanceKnowledgeTransferVolumes;
        },
        shouldFetchKnowledgeTransferVolumes() {
            return this.shouldFetchDistanceKnowledgeTransferVolumes || this.shouldFetchAreaKnowledgeTransferVolumes;
        },
        hasNoKnowledgeTransferVolumes() {
            return this.shouldFetchKnowledgeTransferVolumes && !this.loading && this.knowledgeTransferVolumes.length === 0;
        },
        hasSelectedKnowledgeTransferLabels() {
            return this.selectedKnowledgeTransferLabels.length > 0;
        },
        knowledgeTransferLabels() {
            if (!this.knowledgeTransferVolume) {
                return [];
            }

            let volumeId = this.knowledgeTransferVolume.id;

            if (!this.knowledgeTransferLabelCache.hasOwnProperty(volumeId)) {
                // Initialize empty, will be filled by fetchKnowledgeTransferLabels().
                this.knowledgeTransferLabelCache[volumeId] = [];
                this.fetchKnowledgeTransferLabels(volumeId);
            }

            return this.knowledgeTransferLabelCache[volumeId];
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
        handleSelectedKnowledgeTransferVolume(volume) {
            this.knowledgeTransferVolume = volume;
            this.selectedKnowledgeTransferLabels = [];
        },
        parseKnowledgeTransferVolumes(response) {
            return response.body
                .filter(volume => volume.id !== this.volumeId)
                .map(function (volume) {
                    volume.description = volume.projects
                        .map(project => project.name)
                        .join(', ');

                    return volume;
                });
        },
        fetchLabels(id) {
            this.startLoading();
            let promise = VolumesApi.queryAnnotationLabels({id});
            promise.finally(this.finishLoading);

            return promise;
        },
        fetchKnowledgeTransferLabels(id) {
            this.fetchLabels(id)
                .then((response) => {
                    this.knowledgeTransferLabelCache[id] = response.body;
                }, handleErrorResponse);
        },
        handleSelectedKnowledgeTransferLabel(label) {
            if (this.selectedKnowledgeTransferLabels.indexOf(label) === -1) {
                this.selectedKnowledgeTransferLabels.push(label);
            }
        },
        handleUnselectKnowledgeTransferLabel(label) {
            let index = this.selectedKnowledgeTransferLabels.indexOf(label);
            if (index >= 0) {
                this.selectedKnowledgeTransferLabels.splice(index, 1);
            }
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
                this.fetchLabels(this.volumeId)
                    .then(this.setLabels, handleErrorResponse);
            }
        },
        trainingDataMethod(method, oldMethod) {
            if (method === 'knowledge_transfer') {
                this.shouldFetchDistanceKnowledgeTransferVolumes = true;
            } else if (method === 'area_knowledge_transfer') {
                this.shouldFetchAreaKnowledgeTransferVolumes = true;
            }

            if (oldMethod === 'knowledge_transfer' || oldMethod === 'area_knowledge_transfer') {
                this.knowledgeTransferVolume = null;
                this.selectedKnowledgeTransferLabels = [];
                this.$refs.ktTypeahead.clear();
            }
        },
        shouldFetchDistanceKnowledgeTransferVolumes(fetch) {
            if (fetch) {
                this.startLoading();
                knowledgeTransferVolumeApi.get()
                    .then(this.parseKnowledgeTransferVolumes, handleErrorResponse)
                    .then((volumes) => this.distanceKnowledgeTransferVolumes = volumes)
                    .finally(this.finishLoading);
            }
        },
        shouldFetchAreaKnowledgeTransferVolumes(fetch) {
            if (fetch) {
                this.startLoading();
                areaKnowledgeTransferVolumeApi.get()
                    .then(this.parseKnowledgeTransferVolumes, handleErrorResponse)
                    .then((volumes) => this.areaKnowledgeTransferVolumes = volumes)
                    .finally(this.finishLoading);
            }
        },
    },
    created() {
        this.volumeId = biigle.$require('maia.volumeId');
        this.showAdvanced = biigle.$require('maia.hasErrors');
        this.trainingDataMethod = biigle.$require('maia.trainingDataMethod');

        if (this.useExistingAnnotations) {
            this.shouldFetchLabels = true;
        }
    },
};
</script>
