/**
 * A variant of the annotation canvas used for the refinement of training proposals and
 * annotation candidates.
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineCanvas', {
    mixins: [biigle.$require('annotations.components.annotationCanvas')],
    props: {
        unselectedAnnotations: {
            type: Array,
            default: function () {
                return [];
            },
        },
    },
    data: function () {
        return {
            selectingMaiaAnnotation: false,
        };
    },
    computed: {
        hasAnnotations: function () {
            return this.annotations.length > 0;
        },
    },
    methods: {
        handlePreviousImage: function (e) {
            this.$emit('previous-image');
        },
        handleNextImage: function (e) {
            this.$emit('next-image');
        },
        toggleSelectingMaiaAnnotation: function () {
            this.selectingMaiaAnnotation = !this.selectingMaiaAnnotation;
        },
        createUnselectedAnnotationsLayer: function () {
            this.unselectedAnnotationFeatures = new ol.Collection();
            this.unselectedAnnotationSource = new ol.source.Vector({
                features: this.unselectedAnnotationFeatures
            });
            this.unselectedAnnotationLayer = new ol.layer.Vector({
                source: this.unselectedAnnotationSource,
                // Should be below regular annotations which are at index 100.
                zIndex: 50,
                updateWhileAnimating: true,
                updateWhileInteracting: true,
                style: biigle.$require('annotations.stores.styles').editing,
                opacity: 0.5,
            });
        },
        createSelectMaiaAnnotationInteraction: function (features) {
            var Interaction = biigle.$require('annotations.ol.AttachLabelInteraction');
            this.selectMaiaAnnotationInteraction = new Interaction({
                map: this.map,
                features: features
            });
            this.selectMaiaAnnotationInteraction.setActive(false);
            this.selectMaiaAnnotationInteraction.on('attach', this.handleSelectMaiaAnnotation);
        },
        handleSelectMaiaAnnotation: function (e) {
            this.$emit('select', e.feature.get('annotation'));
        },
        handleUnselectMaiaAnnotation: function () {
            if (this.selectedAnnotations.length > 0) {
                this.$emit('unselect', this.selectedAnnotations[0]);
            }
        },
    },
    watch: {
        unselectedAnnotations: function (annotations) {
            this.refreshAnnotationSource(annotations, this.unselectedAnnotationSource);
        },
        selectingMaiaAnnotation: function (selecting) {
            this.selectMaiaAnnotationInteraction.setActive(selecting);
        },
    },
    created: function () {
        this.createUnselectedAnnotationsLayer();
        this.map.addLayer(this.unselectedAnnotationLayer);

        // Disallow unselecting of currently highlighted training proposal.
        this.selectInteraction.setActive(false);

        if (this.canModify) {
            this.createSelectMaiaAnnotationInteraction(this.unselectedAnnotationFeatures);
            this.map.addInteraction(this.selectMaiaAnnotationInteraction);
            biigle.$require('keyboard').on('Delete', this.handleUnselectMaiaAnnotation, 0, this.listenerSet);
        }
    },
});
