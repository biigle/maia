<script>
import Collection from 'ol/Collection';
import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
import {AnnotationCanvas} from '../import';
import {AttachLabelInteraction} from '../import';
import {Keyboard} from '../import';
import {StylesStore} from '../import';

/**
 * A variant of the annotation canvas used for the refinement of training proposals and
 * annotation candidates.
 *
 * @type {Object}
 */
export default {
    mixins: [AnnotationCanvas],
    props: {
        unselectedAnnotations: {
            type: Array,
            default() {
                return [];
            },
        },
    },
    data() {
        return {
            selectingMaiaAnnotation: false,
        };
    },
    computed: {
        hasAnnotations() {
            return this.annotations.length > 0;
        },
    },
    methods: {
        handlePreviousImage() {
            this.$emit('previous-image');
        },
        handleNextImage() {
            this.$emit('next-image');
        },
        toggleSelectingMaiaAnnotation() {
            this.selectingMaiaAnnotation = !this.selectingMaiaAnnotation;
        },
        createUnselectedAnnotationsLayer() {
            this.unselectedAnnotationFeatures = new Collection();
            this.unselectedAnnotationSource = new VectorSource({
                features: this.unselectedAnnotationFeatures
            });
            this.unselectedAnnotationLayer = new VectorLayer({
                source: this.unselectedAnnotationSource,
                // Should be below regular annotations which are at index 100.
                zIndex: 99,
                updateWhileAnimating: true,
                updateWhileInteracting: true,
                style: StylesStore.editing,
                opacity: 0.5,
            });
        },
        createSelectMaiaAnnotationInteraction(features) {
            this.selectMaiaAnnotationInteraction = new AttachLabelInteraction({
                map: this.map,
                features: features,
            });
            this.selectMaiaAnnotationInteraction.setActive(false);
            this.selectMaiaAnnotationInteraction.on('attach', this.handleSelectMaiaAnnotation);
        },
        handleSelectMaiaAnnotation(e) {
            this.$emit('select', e.feature.get('annotation'));
        },
        handleUnselectMaiaAnnotation() {
            if (this.selectedAnnotations.length > 0) {
                this.$emit('unselect', this.selectedAnnotations[0]);
            }
        },
    },
    watch: {
        unselectedAnnotations(annotations) {
            this.refreshAnnotationSource(annotations, this.unselectedAnnotationSource);
        },
        selectingMaiaAnnotation(selecting) {
            this.selectMaiaAnnotationInteraction.setActive(selecting);
        },
    },
    created() {
        this.createUnselectedAnnotationsLayer();
        this.map.addLayer(this.unselectedAnnotationLayer);

        // Disallow unselecting of currently highlighted training proposal.
        this.selectInteraction.setActive(false);

        if (this.canModify) {
            this.createSelectMaiaAnnotationInteraction(this.unselectedAnnotationFeatures);
            this.map.addInteraction(this.selectMaiaAnnotationInteraction);
            Keyboard.on('Delete', this.handleUnselectMaiaAnnotation, 0, this.listenerSet);
        }

        // Disable shortcut for the measure interaction.
        Keyboard.off('Shift+f', this.toggleMeasuring, this.listenerSet);
    },
    mounted() {
        // Disable shortcut for the translate interaction.
        Keyboard.off('m', this.toggleTranslating, this.listenerSet);
    },
};
</script>
