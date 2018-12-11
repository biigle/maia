/**
 * Resource for annotation candidates.
 *
 * var resource = biigle.$require('maia.api.annotationCandidate');
 *
 * Get the image patch of the candidate:
 * resource.getFile({id: candidateId}).then(...);
 *
 * Update the candidate:
 * resource.update({id: 1}, {label_id: 123}).then(...);
 *
 * @type {Vue.resource}
 */
biigle.$declare('maia.api.annotationCandidate', Vue.resource('api/v1/maia/annotation-candidates{/id}', {}, {
    getFile: {
        method: 'GET',
        url: 'api/v1/maia/annotation-candidates{/id}/file',
    },
}));
