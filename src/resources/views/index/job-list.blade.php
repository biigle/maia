<div class="list-group">
    @foreach ($jobs as $job)
        <a href="{{route('maia', $job->id)}}" class="list-group-item @if ($job->requiresAction()) list-group-item-warning @endif">
            <span class="pull-right">@include('maia::mixins.job-state', ['job' => $job])</span>
            Job #{{$job->id}} created <span title="{{$job->created_at->toIso8601String()}}">{{$job->created_at->diffForHumans()}}</span>
            @if ($job->description)
                <br>{{$job->description}}
            @endif
        </a>
    @endforeach
</div>
