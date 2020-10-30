<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Volume;
use DB;
use Illuminate\Http\Request;

class KnowledgeTransferVolumeController extends Controller
{
    /**
     * List all volumes that can be accessed and used for knowledge transfer.
     *
     * @api {get} volumes/filter/knowledge-transfer Get volumes for knowledge transfer
     * @apiGroup Maia
     * @apiName IndexKnowledgeTransferVolumes
     * @apiPermission user
     * @apiDescription These are volumes where all images have `distance_to_ground` information.
     *
     * @apiSuccessExample {json} Success response:
     * [
     *     {
     *         "id": 1,
     *         "name": "My Volume",
     *         "projects": [
     *             {
     *                 "id": 123,
     *                 "name": "My Project"
     *             }
     *         ]
     *     }
     * ]
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Volume::accessibleBy($request->user())
            ->select('id', 'name')
            ->has('images.annotations')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('images')
                    ->whereRaw('images.volume_id = volumes.id')
                    ->whereNull('attrs->metadata->distance_to_ground');
            })
            ->with(['projects' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get()
            ->each(function ($volume) {
                $volume->setHidden(['doi', 'video_link', 'gis_link']);
            });
    }
}
