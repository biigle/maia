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
    computed: {
        //
    },
    methods: {
        toggleMarkAsInteresting: function () {
            console.log('toggle');
        },
    },
    watch: {
        unselectedAnnotations: function (annotations) {
            this.refreshAnnotationSource(annotations, this.unselectedAnnotationSource);
        },
    },
    created: function () {
        var map = biigle.$require('annotations.stores.map');
        var styles = biigle.$require('annotations.stores.styles');

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
            style: styles.editing,
            opacity: 0.5,
        });
        map.addLayer(this.unselectedAnnotationLayer);
    },
});
