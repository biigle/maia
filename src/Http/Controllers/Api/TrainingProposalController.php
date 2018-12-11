<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Http\Requests\ContinueMaiaJob;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Http\Requests\UpdateTrainingProposal;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class TrainingProposalController extends Controller
{
    /**
     * Get all training proposals of a MAIA job.
     *
     * @api {get} maia-jobs/:id/training-proposals Get training proposals
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
     *         "image_id", 123
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
            ->select('id', 'selected', 'image_id')
            ->orderBy('score', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Continue a MAIA job from training proposal selection and refinement to instance segmentation.
     *
     * @api {post} maia-jobs/:id/training-proposals Submit training proposals
     * @apiGroup Maia
     * @apiName ContinueMaiaJob
     * @apiPermission projectEditor
     * @apiDescription A job can only be continued if it is in training proposal selection and refinement state, and if it has selected training proposals.
     *
     * @apiParam {Number} id The job ID.
     *
     * @param ContinueMaiaJob $request
     * @return \Illuminate\Http\Response
     */
    public function submit(ContinueMaiaJob $request)
    {
        $request->job->state_id = State::instanceSegmentationId();
        $request->job->save();
        event(new MaiaJobContinued($request->job));

        if (!$this->isAutomatedRequest()) {
            return $this->fuzzyRedirect('maia', $request->job->id);
        }
    }

    /**
     * Update a training proposal.
     *
     * @api {put} maia/training-proposals/:id Update a training proposal
     * @apiGroup Maia
     * @apiName UpdateTrainingProposal
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The training proposal ID.
     * @apiParam (Attributes that can be updated) {Boolean} selected Determine whether the proposal has been selected by the user or not.
     * @apiParam (Attributes that can be updated) {Number[]} points Array containing three numbers representing the x- and y-coordinates as well as the radius of the training proposal circle.
     *
     * @param UpdateTrainingProposal $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrainingProposal $request)
    {
        if ($request->filled('points')) {
            $request->proposal->points = $request->input('points');
            GenerateAnnotationPatch::dispatch($request->proposal, $request->proposal->getPatchPath());
        }

        $request->proposal->selected = $request->input('selected', $request->proposal->selected);
        $request->proposal->save();
    }

    /**
     * Get the image patch file of a training proposal.
     *
     * @api {get} maia/training-proposals/:id/file Get a training proposal patch
     * @apiGroup Maia
     * @apiName ShowTrainingProposalFile
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The training proposal ID.
     *
     * @param int $id Training proposal ID
     * @return \Illuminate\Http\Response
     */
    public function showFile($id)
    {
        $a = TrainingProposal::findOrFail($id);
        $this->authorize('access', $a);

        try {
            return response()->download($a->getPatchPath());
        } catch (FileNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }
}
