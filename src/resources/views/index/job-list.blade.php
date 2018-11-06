<div class="list-group">
    @foreach ($jobs as $job)
        <a href="#" class="list-group-item @if ($job->requiresAction()) list-group-item-warning @endif">
            Job #{{$job->id}} started <span title="{{$job->created_at->toIso8601String()}}">{{$job->created_at->diffForHumans()}}</span>
            @if ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::noveltyDetectionId())
                <strong class="text-warning pull-right">running novelty detection</strong>
            @elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::trainingProposalsId())
                <strong class="text-warning pull-right">waiting for training proposal selection</strong>
            @elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::instanceSegmentationId())
                <strong class="text-warning pull-right">running instance segmentation</strong>
            @elseif ($job->state_id === \Biigle\Modules\Maia\MaiaJobState::annotationCandidatesId())
                <strong class="text-warning pull-right">waiting for annotation candidate selection</strong>
            @else
                <strong class="text-success pull-right">finished</strong>
            @endif
        </a>
    @endforeach
</div>
