/**
 * A variant of the image grid used for the selection of MAIA annotation candidates.
 *
 * @type {Object}
 */
biigle.$component('maia.components.acImageGrid', {
    mixins: [biigle.$require('volumes.components.imageGrid')],
    components: {
        imageGridImage: biigle.$require('maia.components.acImageGridImage'),
    },
});
