<?php

namespace Biigle\Modules\Maia\Http\Controllers\Views;

use Biigle\Http\Controllers\Views\Controller;
use Biigle\ImageAnnotation;
use Biigle\LabelTree;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Project;
use Biigle\Role;
use Biigle\Volume;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;

class MaiaJobController extends Controller
{
    /**
     * Show the overview of MAIA jobs for a volume
     *
     * @param Request $request
     * @param int $id Volume ID
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $volume = Volume::findOrFail($id);
        if (!$request->user()->can('sudo')) {
            $this->authorize('edit-in', $volume);
        }

        if (!$volume->isImageVolume() || $volume->hasTiledImages()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $jobs = MaiaJob::where('volume_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        $hasJobsInProgress = $jobs
            ->whereIn('state_id', [
                State::noveltyDetectionId(),
                State::trainingProposalsId(),
                State::objectDetectionId(),
            ])
            ->count() > 0;

        $hasJobsRunning = $jobs
            ->whereIn('state_id', [
                State::noveltyDetectionId(),
                State::objectDetectionId(),
            ])
            ->count() > 0;

        $newestJobHasFailed = $jobs->isNotEmpty() ? $jobs[0]->hasFailed() : false;

        $canUseExistingAnnotations = ImageAnnotation::join('images', 'images.id', '=', 'image_annotations.image_id')
            ->where('images.volume_id', $volume->id)
            ->exists();

        $canUseKnowledgeTransfer = !$volume->images()
            ->whereNull('attrs->metadata->distance_to_ground')
            ->exists();

        $canUseAreaKnowledgeTransfer = !$volume->images()
            ->whereNull(DB::raw("COALESCE(attrs->'metadata'->>'area', attrs->'laserpoints'->>'area')"))
            ->exists();

        $maintenanceMode = config('maia.maintenance_mode');

        return view('maia::index', compact(
            'volume',
            'jobs',
            'hasJobsInProgress',
            'hasJobsRunning',
            'newestJobHasFailed',
            'canUseExistingAnnotations',
            'canUseKnowledgeTransfer',
            'canUseAreaKnowledgeTransfer',
            'maintenanceMode'
        ));
    }

    /**
     * Show a MAIA job
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $job = MaiaJob::findOrFail($id);
        $this->authorize('access', $job);
        $volume = $job->volume;
        $states = State::pluck('id', 'name');

        $user = $request->user();

        if ($job->state_id === State::annotationCandidatesId()) {
            if ($user->can('sudo')) {
                // Global admins have no restrictions.
                $projectIds = $volume->projects()->pluck('id');
            } else {
                // Array of all project IDs that the user and the image have in common
                // and where the user is editor, expert or admin.
                $projectIds = Project::inCommon($user, $volume->id, [
                    Role::editorId(),
                    Role::expertId(),
                    Role::adminId(),
                ])->pluck('id');
            }

            // All label trees that are used by all projects which are visible to the
            // user.
            $trees = LabelTree::select('id', 'name', 'version_id')
                ->with('labels', 'version')
                ->whereIn('id', function ($query) use ($projectIds) {
                    $query->select('label_tree_id')
                        ->from('label_tree_project')
                        ->whereIn('project_id', $projectIds);
                })
                ->get();
        } else {
            $trees = collect([]);
        }

        $tpUrlTemplate = Storage::disk(config('maia.training_proposal_storage_disk'))
            ->url(':prefix/:id.'.config('largo.patch_format'));

        $acUrlTemplate = Storage::disk(config('maia.annotation_candidate_storage_disk'))
            ->url(':prefix/:id.'.config('largo.patch_format'));

        $tpLimit = config('maia.training_proposal_limit');

        return view('maia::show', compact(
            'job',
            'volume',
            'states',
            'trees',
            'tpUrlTemplate',
            'acUrlTemplate',
            'tpLimit'
        ));
    }
}
