<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Http\Requests\SubmitAnnotationCandidates;
use Biigle\Modules\Maia\Http\Requests\UpdateAnnotationCandidate;
use Biigle\Modules\Maia\Jobs\ConvertAnnotationCandidates;
use Biigle\Modules\Maia\MaiaJob;
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
                $candidate->addHidden('label_id');
            })
            ->toArray();
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
        if ($request->filled('points')) {
            $request->candidate->points = $request->input('points');
            $disk = config('maia.annotation_candidate_storage_disk');
            GenerateAnnotationPatch::dispatch($request->candidate, $disk)
                ->onQueue(config('largo.generate_annotation_patch_queue'));
        }

        if ($request->has('label_id')) {
            $request->candidate->label_id = $request->input('label_id');
        }

        $request->candidate->save();
    }
}
