<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use DB;
use Carbon\Carbon;
use Biigle\Annotation;
use Biigle\AnnotationLabel;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Http\Requests\UpdateAnnotationCandidate;
use Biigle\Modules\Maia\Http\Requests\SubmitAnnotationCandidates;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

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
            ->select('id', 'image_id', 'label_id', 'annotation_id')
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
     * @apiDescription This converts all annotation candidates with attached label which have not been converted yet. Returns a map of converted annotation candidate ID to newly created annotation ID.
     *
     * @apiParam {Number} id The job ID.
     *
     * @param SubmitAnnotationCandidates $request
     * @return \Illuminate\Http\Response
     */
    public function submit(SubmitAnnotationCandidates $request)
    {
        $annotations = DB::transaction(function () use ($request) {
            $candidates = $request->job->annotationCandidates()
                ->whereNull('annotation_id')
                ->whereNotNull('label_id')
                ->get();

            $now = Carbon::now();
            $annotations = [];
            $annotationLabels = [];

            foreach ($candidates as $candidate) {
                $annotation = new Annotation;
                $annotation->image_id = $candidate->image_id;
                $annotation->shape_id = $candidate->shape_id;
                $annotation->points = $candidate->points;
                $annotation->save();
                $annotations[$candidate->id] = $annotation;

                $candidate->annotation_id = $annotation->id;
                $candidate->save();

                $annotationLabels[] = [
                    'annotation_id' => $annotation->id,
                    'label_id' => $candidate->label_id,
                    'user_id' => $request->user()->id,
                    'confidence' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            AnnotationLabel::insert($annotationLabels);

            return $annotations;
        });

        foreach ($annotations as $annotation) {
            GenerateAnnotationPatch::dispatch($annotation);
        }

        return array_map(function ($a) {
            return $a->id;
        }, $annotations);
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
            GenerateAnnotationPatch::dispatch($request->candidate, $request->candidate->getPatchPath());
        }

        if ($request->has('label_id')) {
            $request->candidate->label_id = $request->input('label_id');
        }

        $request->candidate->save();
    }

    /**
     * Get the image patch file of a annotation candidate.
     *
     * @api {get} maia/annotation-candidates/:id/file Get an annotation candidate patch
     * @apiGroup Maia
     * @apiName ShowTrainingProposalFile
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The annotation candidate ID.
     *
     * @param int $id Annotation candidate ID
     * @return \Illuminate\Http\Response
     */
    public function showFile($id)
    {
        $a = AnnotationCandidate::findOrFail($id);
        $this->authorize('access', $a);

        try {
            return response()->download($a->getPatchPath());
        } catch (FileNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }
}
