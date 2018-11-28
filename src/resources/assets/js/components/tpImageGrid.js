/**
 * A variant of the image grid used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.tpImageGrid', {
    mixins: [biigle.$require('volumes.components.imageGrid')],
    components: {
        imageGridImage: biigle.$require('maia.components.tpImageGridImage'),
    },
    props: {
        selectedTpIds: {
            type: Object,
            required: true,
        },
    },
});
