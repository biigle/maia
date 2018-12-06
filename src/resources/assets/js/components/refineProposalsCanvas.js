/**
 * A variant of the annotation canvas used for the refinement of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineProposalsCanvas', {
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
            selectingProposal: false,
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
            this.selectingProposal = !this.selectingProposal;
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
        createSelectProposalInteraction: function (features) {
            var Interaction = biigle.$require('annotations.ol.AttachLabelInteraction');
            this.selectProposalInteraction = new Interaction({
                map: this.map,
                features: features
            });
            this.selectProposalInteraction.setActive(false);
            this.selectProposalInteraction.on('attach', this.handleSelectProposal);
        },
        handleSelectProposal: function (e) {
            this.$emit('select', e.feature.get('annotation'));
        },
        handleUnselectProposal: function () {
            if (this.selectedAnnotations.length > 0) {
                this.$emit('unselect', this.selectedAnnotations[0]);
            }
        },
    },
    watch: {
        unselectedAnnotations: function (annotations) {
            this.refreshAnnotationSource(annotations, this.unselectedAnnotationSource);
        },
        selectingProposal: function (selecting) {
            this.selectProposalInteraction.setActive(selecting);
        },
    },
    created: function () {
        this.createUnselectedAnnotationsLayer();
        this.map.addLayer(this.unselectedAnnotationLayer);

        // Disallow unselecting of currently highlighted training proposal.
        this.selectInteraction.setActive(false);

        if (this.canModify) {
            this.createSelectProposalInteraction(this.unselectedAnnotationFeatures);
            this.map.addInteraction(this.selectProposalInteraction);
            biigle.$require('keyboard').on('Delete', this.handleUnselectProposal, 0, this.listenerSet);
        }
    },
});
