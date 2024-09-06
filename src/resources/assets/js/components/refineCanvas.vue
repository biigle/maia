<script>
import Collection from '@biigle/ol/Collection';
import Style from '@biigle/ol/style/Style';
import VectorLayer from '@biigle/ol/layer/Vector';
import VectorSource from '@biigle/ol/source/Vector';
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
        // This is required by the AnnotationCanvas but we don't need it here.
        userId: {
            default: null,
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
            // The style can't be used directly because biigle/maia imports their own
            // @biigle/ol package and the Style object of biigle/core's @biigle/ol does
            // not match this one.
            const editingStyle = StylesStore.editing;
            this.unselectedAnnotationLayer = new VectorLayer({
                source: this.unselectedAnnotationSource,
                // Should be below regular annotations which are at index 100.
                zIndex: 99,
                updateWhileAnimating: true,
                updateWhileInteracting: true,
                style: [
                    new Style({
                        stroke: editingStyle[0].getStroke(),
                        image: editingStyle[0].getImage(),
                    }),
                    new Style({
                        stroke: editingStyle[1].getStroke(),
                    }),
                ],
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
            if (!this.modifyInProgress && this.selectedAnnotations.length > 0) {
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
