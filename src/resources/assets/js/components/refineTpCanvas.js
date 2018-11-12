/**
 * A variant of the annotation canvas used for the refinement of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineTpCanvas', {
    mixins: [biigle.$require('annotations.components.annotationCanvas')],
    methods: {
        toggleMarkAsInteresting: function () {
            console.log('toggle');
        },
    },
});
