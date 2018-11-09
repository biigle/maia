/**
 * Resource for MAIA annotations.
 *
 * var resource = biigle.$require('maia.api.maiaAnnotation');
 *
 * Get the image patch of the annotation:
 * resource.getFile({id: annotationId}).then(...);
 *
 * Update the annotation:
 * resource.update({id: 1}, {selected: true}).then(...);
 *
 * @type {Vue.resource}
 */
biigle.$declare('maia.api.maiaAnnotation', Vue.resource('api/v1/maia-annotations{/id}', {}, {
    getFile: {
        method: 'GET',
        url: 'api/v1/maia-annotations{/id}/file',
    },
}));
