<?php

namespace Biigle\Modules\Maia\Http\Controllers\Views;

use Biigle\Volume;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Http\Controllers\Views\Controller;
use Biigle\Modules\Maia\MaiaJobState as State;

class MaiaJobController extends Controller
{
    /**
     * Show the overview of MAIA jobs for a volume
     *
     * @param int $id Volume ID
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $volume = Volume::findOrFail($id);
        $this->authorize('edit-in', $volume);

        if ($volume->hasTiledImages()) {
            abort(404);
        }

        $jobs = MaiaJob::where('volume_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        $hasJobsInProgress = $jobs
            ->whereIn('state_id', [
                State::noveltyDetectionId(),
                State::trainingProposalsId(),
                State::instanceSegmentationId(),
            ])
            ->count() > 0;

        $hasJobsRunning = $jobs
            ->whereIn('state_id', [
                State::noveltyDetectionId(),
                State::instanceSegmentationId(),
            ])
            ->count() > 0;

        $newestJobHasFailed = $jobs->isNotEmpty() ? $jobs[0]->hasFailed() : false;

        return view('maia::index', compact(
            'volume',
            'jobs',
            'hasJobsInProgress',
            'hasJobsRunning',
            'newestJobHasFailed'
        ));
    }

    /**
     * Show a MAIA job
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = MaiaJob::findOrFail($id);
        $this->authorize('access', $job);
        $volume = $job->volume;
        $states = State::pluck('id', 'name');

        return view('maia::show', compact('job', 'volume', 'states'));
    }
}
