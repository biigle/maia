<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Http\Controllers\Api\Controller;

class TrainingProposalController extends Controller
{
    /**
     * Get all training proposals of a MAIA job.
     *
     * @api {get} maia-jobs/:id/training-proposals
     * @apiGroup Maia
     * @apiName IndexMaiaTrainingProposals
     * @apiPermission projectEditor
     * @apiDescription The training proposals are ordered by descending score.
     *
     * @apiParam {Number} id The job ID.
     *
     * @apiSuccessExample {json} Success response:
     * [
     *     {
     *         "id": 1,
     *         "selected": false,
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

        return $job->trainingProposals()
            ->select('id', 'selected')
            ->orderBy('score', 'desc')
            ->get()
            ->toArray();
    }
}
