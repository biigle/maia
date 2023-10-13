/**
 * Resource for MAIA jobs.
 *
 * let resource = biigle.$require('maia.api.maiaJob');
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
 * Get training proposal IDs sorted by similarity to a reference proposal:
 * resource.getSimilarTrainingProposals({jobId: 1, proposalId: 2}).then(...);
 *
 * Get coordinates of training proposals for an image belonging to the job:
 * resource.getTrainingProposalPoints({jobId: 1, imageId: 2}).then(...);
 *
 * Delete a MAIA job:
 * resource.delete({id: 1}).then(...);
 *
 * @type {Vue.resource}
 */
export default Vue.resource('api/v1/maia-jobs{/id}', {}, {
    save: {
        method: 'POST',
        url: 'api/v1/volumes{/id}/maia-jobs',
    },
    getTrainingProposals: {
        method: 'GET',
        url: 'api/v1/maia-jobs{/id}/training-proposals',
    },
    getSimilarTrainingProposals: {
        method: 'GET',
        url: 'api/v1/maia-jobs{/jobId}/training-proposals/similar-to{/proposalId}',
    },
    getTrainingProposalPoints: {
        method: 'GET',
        url: 'api/v1/maia-jobs{/jobId}/images{/imageId}/training-proposals',
    },
    getAnnotationCandidates: {
        method: 'GET',
        url: 'api/v1/maia-jobs{/id}/annotation-candidates',
    },
    getSimilarAnnotationCandidates: {
        method: 'GET',
        url: 'api/v1/maia-jobs{/jobId}/annotation-candidates/similar-to{/candidateId}',
    },
    getAnnotationCandidatePoints: {
        method: 'GET',
        url: 'api/v1/maia-jobs{/jobId}/images{/imageId}/annotation-candidates',
    },
    convertAnnotationCandidates: {
        method: 'POST',
        url: 'api/v1/maia-jobs{/id}/annotation-candidates',
    },
    convertingAnnotationCandidates: {
        method: 'GET',
        url: 'api/v1/maia-jobs{/id}/converting-candidates',
    },
});
