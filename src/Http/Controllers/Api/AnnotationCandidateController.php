<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\Http\Requests\SubmitAnnotationCandidates;
use Biigle\Modules\Maia\Http\Requests\UpdateAnnotationCandidate;
use Biigle\Modules\Maia\Jobs\ConvertAnnotationCandidates;
use Biigle\Modules\Maia\Jobs\ProcessObjectDetectedImage;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Http\Response;
use Pgvector\Laravel\Distance;
use Queue;

class AnnotationCandidateController extends Controller
{
    /**
     * Get all annotation candidates of a MAIA job.
     *
     * @api {get} maia-jobs/:id/annotation-candidates Get annotation candidates
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
     *         "label": null,
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
            ->join('images', 'images.id', '=', 'maia_annotation_candidates.image_id')
            ->select(
                'maia_annotation_candidates.id',
                'maia_annotation_candidates.image_id',
                'maia_annotation_candidates.label_id',
                'maia_annotation_candidates.annotation_id',
                'images.uuid as uuid'
            )
            ->orderBy('score', 'desc')
            ->with('label')
            ->get()
            ->each(function ($candidate) {
                $candidate->makeHidden('label_id');
            })
            ->toArray();
    }

    /**
     * Get annotation candidates ordered by similarity to a specific proposal.
     *
     * @api {get} maia-jobs/:id/annotation-candidates/similar-to/:id2 Get similar annotation candidates
     * @apiGroup Maia
     * @apiName IndexSimilarMaiaAnnotationCandidates
     * @apiPermission projectEditor
     * @apiDescription This endpoints returns the ordered annotation candidate IDs (except the ID of the reference annotation candidate). Candidates without feature vectors for similarity computation are appended at the end.
     *
     * @apiParam {Number} id The job ID.
     * @apiParam {Number} id2 The "reference" annotation candidate ID.
     *
     * @param int $id Job ID
     * @param int $id2 Training proposal ID
     * @return \Illuminate\Http\Response
     */
    public function indexSimilar($id, $id2)
    {
        $job = MaiaJob::findOrFail($id);
        $this->authorize('access', $job);

        $feature = AnnotationCandidateFeatureVector::where('job_id', $id)
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

        if ($count !== ($job->annotationCandidates()->count() - 1)) {
            // Add IDs of candidates without feature vectors at the end.
            $ids = $ids->concat(
                $job->annotationCandidates()
                    ->whereNotIn('id', $ids)
                    ->whereNot('id', $id2)
                    ->pluck('id')
            );
        }

        return $ids;
    }

    /**
     * Convert annotation candidates to annotations.
     *
     * @api {post} maia-jobs/:id/annotation-candidates Convert annotation candidates
     * @apiGroup Maia
     * @apiName ConvertAnnotationCandidates
     * @apiPermission projectEditor
     * @apiDescription This converts all annotation candidates with attached label which have not been converted yet. Conversion is done asynchronously. The process can be polled using (#Maia:GetConvertingAnnotations).
     *
     * @apiParam {Number} id The job ID.
     *
     * @param SubmitAnnotationCandidates $request
     * @return \Illuminate\Http\Response
     */
    public function submit(SubmitAnnotationCandidates $request)
    {
        Queue::push(new ConvertAnnotationCandidates($request->job, $request->user()));
        $request->job->convertingCandidates = true;
        $request->job->save();
    }

    /**
     * Update an annotation candidate.
     *
     * @api {put} maia/annotation-candidates/:id Update an annotation candidate
     * @apiGroup Maia
     * @apiName UpdateAnnotationCandidate
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The annotation candidate ID.
     * @apiParam (Attributes that can be updated) {Number[]} points Array containing three numbers representing the x- and y-coordinates as well as the radius of the annotation candidate circle.
     * @apiParam (Attributes that can be updated) {Number} label_id ID of the label to attach to the annotation candidate. Set to null to detach the label again. This label will be attached to the annotation when the annotation candidate is converted.
     *
     * @param UpdateAnnotationCandidate $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnnotationCandidate $request)
    {
        $candidate = $request->candidate;
        if ($request->filled('points')) {
            $candidate->points = $request->input('points');
            ProcessObjectDetectedImage::dispatch($candidate->image,
                    only: [$candidate->id],
                    maiaJob: $candidate->job,
                    targetDisk: config('maia.annotation_candidate_storage_disk')
                )
                ->onQueue(config('largo.generate_annotation_patch_queue'));
        }

        if ($request->has('label_id')) {
            $candidate->label_id = $request->input('label_id');
        }

        $candidate->save();
    }

    public function updateBatch(Request $request)
{
    $candidates = $request->input('candidates', []);

    foreach ($candidates as $candidateData) {
        $candidate = AnnotationCandidate::find($candidateData['id']);

        if ($candidate) {
            $candidate->label_id = $candidateData['label_id'];
            $candidate->points = $candidateData['points'] ?? $candidate->points;
            $candidate->save();
        }
    }

    return response()->json(['message' => 'Candidates updated successfully'], 200);
}

}