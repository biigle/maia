/**
 * Resource for annotation candidates.
 *
 * var resource = biigle.$require('maia.api.annotationCandidate');
 *
 * Update the candidate:
 * resource.update({id: 1}, {label_id: 123}).then(...);
 *
 * @type {Vue.resource}
 */
biigle.$declare('maia.api.annotationCandidate', Vue.resource('api/v1/maia/annotation-candidates{/id}'));
