import { Resource } from '../import.js';

/**
 * Resource for annotation candidates.
 *
 * let resource = biigle.$require('maia.api.annotationCandidate');
 *
 * Update the candidate:
 * resource.update({id: 1}, {label_id: 123}).then(...);
 */
export default Resource('api/v1/maia/annotation-candidates{/id}');
