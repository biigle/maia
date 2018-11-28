<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Modules\Maia\MaiaAnnotation;
use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Http\Requests\UpdateMaiaAnnotation;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class MaiaAnnotationController extends Controller
{
    /**
     * Get the image patch file of a MAIA annotation.
     *
     * @api {get} maia-annotations/:id/file
     * @apiGroup Maia
     * @apiName ShowMaiaAnnotationFile
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The annotation ID.
     *
     * @param int $id Annotation ID
     * @return \Illuminate\Http\Response
     */
    public function showFile($id)
    {
        $a = MaiaAnnotation::findOrFail($id);
        $this->authorize('access', $a);

        try {
            return response()->download($a->getPatchPath());
        } catch (FileNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Update a MAIA annotation.
     *
     * @api {put} maia-annotations/:id
     * @apiGroup Maia
     * @apiName UpdateMaiaAnnotation
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The annotation ID.
     * @apiParam (Attributes that can be updated) {Boolean} selected Determine whether the annotation has been selected by the user or not.
     * @apiParam (Attributes that can be updated) {Number[]} points Array containing three numbers representing the x- and y-coordinates as well as the radius of the annotation circle.
     *
     * @param UpdateMaiaAnnotation $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaiaAnnotation $request)
    {
        if ($request->filled('points')) {
            $request->annotation->points = $request->input('points');
            GenerateAnnotationPatch::dispatch($request->annotation, $request->annotation->getPatchPath());
        }

        $request->annotation->selected = $request->input('selected', $request->annotation->selected);
        $request->annotation->save();
    }
}
