@if ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::noveltyDetectionId())
    <strong class="text-warning">running novelty detection</strong>
@elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::failedNoveltyDetectionId())
    <strong class="text-danger">failed novelty detection</strong>
@elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::trainingProposalsId())
    <strong class="text-warning">waiting for training proposal selection</strong>
@elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::instanceSegmentationId())
    <strong class="text-warning">running instance segmentation</strong>
@elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::failedInstanceSegmentationId())
    <strong class="text-danger">failed instance segmentation</strong>
@else
    <strong class="text-success">finished</strong>
@endif
