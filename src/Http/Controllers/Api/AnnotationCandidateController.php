<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Http\Controllers\Api\Controller;

class AnnotationCandidateController extends Controller
{
    /**
     * Get all annotation candidates of a MAIA job.
     *
     * @api {get} maia-jobs/:id/annotation-candidates
     * @apiGroup Maia
     * @apiName IndexMaiaAnnotationCandidates
     * @apiPermission projectEditor
     * @apiDescription The annotation candidates are ordered by descending score.
     *
     * @apiParam {Number} id The job ID.
     *
     * @apiSuccessExample {json} Success response:
     * [
     *     {
     *         "id": 1,
     *         "selected": false,
     *         "image_id": 20
     *     }
     * ]
     *
     * @param int $id Job ID
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $job = MaiaJob::findOrFail($id);
        $this->authorize('access', $job);

        return $job->annotationCandidates()
            ->select('id', 'selected', 'image_id')
            ->orderBy('score', 'desc')
            ->get()
            ->toArray();
    }
}
