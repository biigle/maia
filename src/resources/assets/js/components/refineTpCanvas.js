/**
 * A variant of the annotation canvas used for the refinement of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineTpCanvas', {
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
            selectingTp: false,
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
        toggleMarkAsInteresting: function () {
            this.selectingTp = !this.selectingTp;
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
        createSelectTpInteraction: function (features) {
            var Interaction = biigle.$require('annotations.ol.AttachLabelInteraction');
            this.selectTpInteraction = new Interaction({
                map: this.map,
                features: features
            });
            this.selectTpInteraction.setActive(false);
            this.selectTpInteraction.on('attach', this.handleSelectTp);
        },
        handleSelectTp: function (e) {
            this.$emit('select-tp', e.feature.get('annotation'));
        },
        handleUnselectTp: function () {
            if (this.selectedAnnotations.length > 0) {
                this.$emit('unselect-tp', this.selectedAnnotations[0]);
            }
        },
    },
    watch: {
        unselectedAnnotations: function (annotations) {
            this.refreshAnnotationSource(annotations, this.unselectedAnnotationSource);
        },
        selectingTp: function (selecting) {
            this.selectTpInteraction.setActive(selecting);
        },
    },
    created: function () {
        this.createUnselectedAnnotationsLayer();
        this.map.addLayer(this.unselectedAnnotationLayer);

        // Disallow unselecting of currently highlighted training proposal.
        this.selectInteraction.setActive(false);

        if (this.canModify) {
            this.createSelectTpInteraction(this.unselectedAnnotationFeatures);
            this.map.addInteraction(this.selectTpInteraction);
            biigle.$require('keyboard').on('Delete', this.handleUnselectTp, 0, this.listenerSet);
        }
    },
});
