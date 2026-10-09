@use('Biigle\Modules\Maia\MaiaJobState')
@if ($job->state === MaiaJobState::NOVELTY_DETECTION)
    <strong class="text-warning">running novelty detection</strong>
@elseif ($job->state === MaiaJobState::FAILED_NOVELTY_DETECTION)
    <strong class="text-danger">failed novelty detection</strong>
@elseif ($job->state === MaiaJobState::TRAINING_PROPOSALS)
    <strong class="text-warning">waiting for training proposals</strong>
@elseif ($job->state === MaiaJobState::OBJECT_DETECTION)
    <strong class="text-warning">running object detection</strong>
@elseif ($job->state === MaiaJobState::FAILED_OBJECT_DETECTION)
    <strong class="text-danger">failed object detection</strong>
@else
    <strong class="text-success">finished</strong>
@endif
