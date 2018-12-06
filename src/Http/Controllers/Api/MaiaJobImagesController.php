<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Http\Controllers\Api\Controller;

class MaiaJobImagesController extends Controller
{
    /**
     * Get training proposal coordinates for an image.
     *
     * @api {get} maia-jobs/:jid/images/:iid/training-proposals
     * @apiGroup Maia
     * @apiName IndexMaiaImageTrainingProposals
     * @apiPermission projectEditor
     * @apiDescription Training proposals are assumed to have the circle shape. Returns a map of training proposal IDs to their points arrays.
     *
     * @apiParam {Number} jid The job ID.
     * @apiParam {Number} iid The image ID.
     *
     * @apiSuccessExample {json} Success response:
     * {
     *    "1": [19, 28, 37]
     * }
     *
     * @param int $jobId
     * @param int $imageId
     * @return \Illuminate\Http\Response
     */
    public function indexTrainingProposals($jobId, $imageId)
    {
        $job = MaiaJob::findOrFail($jobId);
        $this->authorize('access', $job);

        return $job->trainingProposals()
            ->where('image_id', $imageId)
            ->pluck('points', 'id');
    }

    /**
     * Get annotationcandidate coordinates for an image.
     *
     * @api {get} maia-jobs/:jid/images/:iid/annotation-candidates
     * @apiGroup Maia
     * @apiName IndexMaiaImageAnnotationCandidates
     * @apiPermission projectEditor
     * @apiDescription Annotation candidates are assumed to have the circle shape. Returns a map of annotation candidate IDs to their points arrays.
     *
     * @apiParam {Number} jid The job ID.
     * @apiParam {Number} iid The image ID.
     *
     * @apiSuccessExample {json} Success response:
     * {
     *    "1": [19, 28, 37]
     * }
     *
     * @param int $jobId
     * @param int $imageId
     * @return \Illuminate\Http\Response
     */
    public function indexAnnotationCandidates($jobId, $imageId)
    {
        $job = MaiaJob::findOrFail($jobId);
        $this->authorize('access', $job);

        return $job->annotationCandidates()
            ->where('image_id', $imageId)
            ->pluck('points', 'id');
    }
}
