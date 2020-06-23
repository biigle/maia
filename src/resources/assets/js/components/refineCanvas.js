/**
 * A letiant of the annotation canvas used for the refinement of training proposals and
 * annotation candidates.
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineCanvas', {
    mixins: [biigle.$require('annotations.components.annotationCanvas')],
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
        handlePreviousImage(e) {
            this.$emit('previous-image');
        },
        handleNextImage(e) {
            this.$emit('next-image');
        },
        toggleSelectingMaiaAnnotation() {
            this.selectingMaiaAnnotation = !this.selectingMaiaAnnotation;
        },
        createUnselectedAnnotationsLayer() {
            this.unselectedAnnotationFeatures = new ol.Collection();
            this.unselectedAnnotationSource = new ol.source.Vector({
                features: this.unselectedAnnotationFeatures
            });
            this.unselectedAnnotationLayer = new ol.layer.Vector({
                source: this.unselectedAnnotationSource,
                // Should be below regular annotations which are at index 100.
                zIndex: 99,
                updateWhileAnimating: true,
                updateWhileInteracting: true,
                style: biigle.$require('annotations.stores.styles').editing,
                opacity: 0.5,
            });
        },
        createSelectMaiaAnnotationInteraction(features) {
            let Interaction = biigle.$require('annotations.ol.AttachLabelInteraction');
            this.selectMaiaAnnotationInteraction = new Interaction({
                map: this.map,
                features: features
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
        let kb = biigle.$require('keyboard');

        if (this.canModify) {
            this.createSelectMaiaAnnotationInteraction(this.unselectedAnnotationFeatures);
            this.map.addInteraction(this.selectMaiaAnnotationInteraction);
            kb.on('Delete', this.handleUnselectMaiaAnnotation, 0, this.listenerSet);
        }

        // Disable shortcut for the measure interaction.
        kb.off('Shift+f', this.toggleMeasuring, this.listenerSet);
    },
    mounted() {
        // Disable shortcut for the translate interaction.
        biigle.$require('keyboard').off('m', this.toggleTranslating, this.listenerSet);
    },
});
