<?php

namespace Biigle\Modules\Maia\Http\Controllers\Views;

use Biigle\Volume;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Http\Controllers\Views\Controller;
use Biigle\Modules\Maia\MaiaJobState as State;

class MaiaController extends Controller
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

        $jobRunning = $jobs->where('state_id', '!=', State::finishedId())->count() > 0;

        return view('maia::index', compact(
            'volume',
            'jobs',
            'jobRunning'
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
            'finished' => State::finishedId(),
        ]);

        return view('maia::show', compact('job', 'volume', 'states'));
    }
}
