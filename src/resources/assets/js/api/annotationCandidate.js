/**
 * Resource for annotation candidates.
 *
 * let resource = biigle.$require('maia.api.annotationCandidate');
 *
 * Update the candidate:
 * resource.update({id: 1}, {label_id: 123}).then(...);
 *
 * @type {Vue.resource}
 */
export default Vue.resource('api/v1/maia/annotation-candidates{/id}');
