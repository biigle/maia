<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Http\Requests\ContinueMaiaJob;
use Biigle\Modules\Maia\Http\Requests\UpdateTrainingProposal;
use Biigle\Modules\Maia\Jobs\ProcessNoveltyDetectedImage;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Illuminate\Http\Response;
use Pgvector\Laravel\Distance;

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
            ->join('images', 'images.id', '=', 'maia_training_proposals.image_id')
            ->select(
                'maia_training_proposals.id',
                'maia_training_proposals.selected',
                'maia_training_proposals.image_id',
                'images.uuid as uuid'
            )
            ->orderBy('score', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get training proposals ordered by similarity to a specific proposal.
     *
     * @api {get} maia-jobs/:id/training-proposals/similar-to/:id2 Get similar training proposals
     * @apiGroup Maia
     * @apiName IndexSimilarMaiaTrainingProposals
     * @apiPermission projectEditor
     * @apiDescription This endpoints returns the ordered training proposal IDs (except the ID of the reference training proposal). Proposals without feature vectors for similarity computation are appended at the end.
     *
     * @apiParam {Number} id The job ID.
     * @apiParam {Number} id2 The "reference" training proposal ID.
     *
     * @param int $id Job ID
     * @param int $id2 Annotation candidate ID
     * @return \Illuminate\Http\Response
     */
    public function indexSimilar($id, $id2)
    {
        $job = MaiaJob::findOrFail($id);
        $this->authorize('access', $job);

        $feature = TrainingProposalFeatureVector::where('job_id', $id)
            ->findOrFail($id2);

        // Manually optimized query for the cosine distance. The nearestNeighbors()
        // method of pgvector seems to compute the distances twice and returns lots
        // of data that we don't need.
        $ids = $feature->whereNotNull('vector')
            ->where('id', '!=', $feature->id)
            ->where('job_id', $id)
            ->orderByRaw('vector <=> ?', [$feature->vector])
            ->pluck('id');

        $count = $ids->count();
        if ($count === 0) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($count !== ($job->trainingProposals()->count() - 1)) {
            // Add IDs of proposals without feature vectors at the end.
            $ids = $ids->concat(
                $job->trainingProposals()
                    ->whereNotIn('id', $ids)
                    ->whereNot('id', $id2)
                    ->pluck('id')
            );
        }

        return $ids;
    }

    /**
     * Continue a MAIA job from training proposal selection and refinement to object detection.
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
        $request->job->state_id = State::objectDetectionId();
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
    public function updateMany(Request $request)
    {
        $proposals = $request->input('proposals');
        foreach ($proposals as $proposalData) {
            $proposal = TrainingProposal::find($proposalData['id']);
            if ($proposal) {
                if (isset($proposalData['points'])) {
                    $proposal->points = $proposalData['points'];
                    ProcessNoveltyDetectedImage::dispatch($proposal->image,
                            only: [$proposal->id],
                            maiaJob: $proposal->job,
                            targetDisk: config('maia.training_proposal_storage_disk')
                        )
                        ->onQueue(config('largo.generate_annotation_patch_queue'));
                }
                $proposal->selected = $proposalData['selected'] ?? $proposal->selected;
                $proposal->save();
            }
        }
        return response()->json(['status' => 'success']);
    }


    
}
