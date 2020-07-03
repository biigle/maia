/**
 * Resource for training proposals.
 *
 * let resource = biigle.$require('maia.api.trainingProposal');
 *
 * Update the proposal:
 * resource.update({id: 1}, {selected: true}).then(...);
 *
 * @type {Vue.resource}
 */
export default Vue.resource('api/v1/maia/training-proposals{/id}');
