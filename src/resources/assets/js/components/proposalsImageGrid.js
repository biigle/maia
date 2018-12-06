/**
 * A variant of the image grid used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.proposalsImageGrid', {
    mixins: [biigle.$require('volumes.components.imageGrid')],
    components: {
        imageGridImage: biigle.$require('maia.components.proposalsImageGridImage'),
    },
    props: {
        selectedProposalIds: {
            type: Object,
            required: true,
        },
    },
});
