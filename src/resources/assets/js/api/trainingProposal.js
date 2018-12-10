/**
 * Resource for training proposals.
 *
 * var resource = biigle.$require('maia.api.trainingProposal');
 *
 * Get the image patch of the proposal:
 * resource.getFile({id: proposalId}).then(...);
 *
 * Update the proposal:
 * resource.update({id: 1}, {selected: true}).then(...);
 *
 * @type {Vue.resource}
 */
biigle.$declare('maia.api.trainingProposal', Vue.resource('api/v1/maia/training-proposals{/id}', {}, {
    getFile: {
        method: 'GET',
        url: 'api/v1/maia/training-proposals{/id}/file',
    },
}));
