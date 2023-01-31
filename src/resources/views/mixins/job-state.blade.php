@if ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::noveltyDetectionId())
    <strong class="text-warning">running novelty detection</strong>
@elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::failedNoveltyDetectionId())
    <strong class="text-danger">failed novelty detection</strong>
@elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::trainingProposalsId())
    <strong class="text-warning">waiting for training proposals</strong>
@elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::objectDetectionId())
    <strong class="text-warning">running object detection</strong>
@elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::failedObjectDetectionId())
    <strong class="text-danger">failed object detection</strong>
@else
    <strong class="text-success">finished</strong>
@endif
