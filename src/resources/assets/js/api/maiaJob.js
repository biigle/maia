/**
 * Resource for MAIA jobs.
 *
 * var resource = biigle.$require('maia.api.maiaJob');
 *
 * Create a MAIA job:
 * resource.save({id: volumeId}, {
 *     clusters: 5,
 *     patch_size: 39,
 *     ...
 * }).then(...);
 *
 * Get all training proposals of a job:
 * resource.getTrainingProposals({id: 1}).then(...);
 *
 * Delete a MAIA job:
 * resource.delete({id: 1}).then(...);
 *
 * @type {Vue.resource}
 */
biigle.$declare('maia.api.maiaJob', Vue.resource('api/v1/maia{/id}', {}, {
    save: {
        method: 'POST',
        url: 'api/v1/volumes{/id}/maia',
    },
    getTrainingProposals: {
        method: 'GET',
        url: 'api/v1/maia{/id}/training-proposals',
    },
}));
