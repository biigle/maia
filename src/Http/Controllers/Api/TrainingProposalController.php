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

        $query = $job->trainingProposals()
            ->join('images', 'images.id', '=', 'maia_training_proposals.image_id')
            ->select(
                'maia_training_proposals.id',
                'maia_training_proposals.selected',
                'maia_training_proposals.image_id',
                'images.uuid as uuid'
            )
            ->orderBy('score', 'desc')
            ->orderBy('id', 'desc');

        $yieldItems = function () use ($query): \Generator {
            foreach ($query->lazy(10000) as $item) {
                yield $item;
            }
        };

        // Use a streamed response because there can be a lot of items.
        return response()->streamJson($yieldItems());
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

        $feature = TrainingProposalFeatureVector::findOrFail($id2);
        $query = TrainingProposalFeatureVector::where('job_id', $id);
        $hasFeatures = $query->clone()
            ->whereNotNull('vector')
            ->whereNot('id', $id2)
            ->exists();

        if (!$hasFeatures) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $yieldItems = function () use ($query, $feature, $job): \Generator {
            // Manually optimized query for the cosine distance. The nearestNeighbors()
            // method of pgvector seems to compute the distances twice and returns lots
            // of data that we don't need.
            $idsQuery = $query->clone()
                ->whereNotNull('vector')
                ->whereNot('id', $feature->id)
                ->orderByRaw('vector <=> ?', [$feature->vector])
                ->orderBy('id')
                ->select('id');

            foreach ($idsQuery->lazy(10000) as $item) {
                yield $item->id;
            }

            // Return early to avoid running the very expensive query below.
            if ($query->clone()->count() === $job->trainingProposals()->count()) {
                return;
            }

            // Add IDs of candidates without feature vectors at the end.
            $remainingIdsQuery = $job->trainingProposals()
                ->whereNotIn('id', function ($query) use ($job) {
                    $query->select('id')
                        ->from('maia_training_proposal_feature_vectors')
                        ->where('job_id', $job->id);
                });

            foreach ($remainingIdsQuery->lazyById(10000) as $item) {
                yield $item->id;
            }
        };

        // Use a streamed response because there can be a lot of items.
        return response()->streamJson($yieldItems());
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
    public function update(UpdateTrainingProposal $request)
    {
        $proposal = $request->proposal;
        if ($request->filled('points')) {
            $proposal->points = $request->input('points');
            ProcessNoveltyDetectedImage::dispatch($proposal->image,
                    only: [$proposal->id],
                    maiaJob: $proposal->job,
                    targetDisk: config('maia.training_proposal_storage_disk')
                )
                ->onQueue(config('largo.generate_annotation_patch_queue'));
        }

        $proposal->selected = $request->input('selected', $proposal->selected);

        $proposal->save();
    }
}
