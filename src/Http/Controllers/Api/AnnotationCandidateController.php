<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Http\Requests\UpdateAnnotationCandidate;
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
            ->select('id', 'image_id')
            ->orderBy('score', 'desc')
            ->get()
            ->toArray();
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

        // TODO set label
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
