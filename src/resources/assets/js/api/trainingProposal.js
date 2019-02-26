/**
 * Resource for training proposals.
 *
 * var resource = biigle.$require('maia.api.trainingProposal');
 *
 * Update the proposal:
 * resource.update({id: 1}, {selected: true}).then(...);
 *
 * @type {Vue.resource}
 */
biigle.$declare('maia.api.trainingProposal', Vue.resource('api/v1/maia/training-proposals{/id}'));
