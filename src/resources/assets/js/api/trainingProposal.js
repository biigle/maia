import { Resource } from '../import.js';

/**
 * Resource for training proposals.
 *
 * let resource = biigle.$require('maia.api.trainingProposal');
 *
 * Update the proposal:
 * resource.update({id: 1}, {selected: true}).then(...);
 */
export default Resource('api/v1/maia/training-proposals{/id}');
