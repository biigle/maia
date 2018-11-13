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

        $jobs = MaiaJob::where('volume_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        $hasUnfinishedJobs = $jobs
            ->where('state_id', '!=', State::annotationCandidatesId())
            ->count() > 0;

        $hasProcessingJobs = $jobs
            ->whereIn('state_id', [
                State::noveltyDetectionId(),
                State::instanceSegmentationId(),
            ])
            ->count() > 0;

        return view('maia::index', compact(
            'volume',
            'jobs',
            'hasUnfinishedJobs',
            'hasProcessingJobs'
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
        $states = collect([
            'novelty-detection' => State::noveltyDetectionId(),
            'training-proposals' => State::trainingProposalsId(),
            'instance-segmentation' => State::instanceSegmentationId(),
            'annotation-candidates' => State::annotationCandidatesId(),
        ]);

        return view('maia::show', compact('job', 'volume', 'states'));
    }
}
