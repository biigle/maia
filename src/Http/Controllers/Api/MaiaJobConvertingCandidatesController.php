<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Http\Response;

class MaiaJobConvertingCandidatesController extends Controller
{
    /**
     * Check whether a job to convert candidates is running
     *
     * @api {delete} maia-jobs/:id/converting-candidates Check for convert candidates job
     * @apiGroup Maia
     * @apiName GetConvertingAnnotations
     * @apiPermission projectEditor
     * @apiDescription This endpoint returns a success code if a job to convert annotation candidates is still running and 404 if not (or if the MAIA job does not exist).
     *
     * @apiParam {Number} id The job ID.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $job = MaiaJob::findOrFail($id);
        $this->authorize('access', $job);
        if (!$job->convertingCandidates) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }
}
