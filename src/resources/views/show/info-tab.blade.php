<div class="sidebar-tab__content">
    <p>
        Job #{{$job->id}} created <span title="{{$job->created_at->toIso8601String()}}">{{$job->created_at->diffForHumans()}}<br>by {{$job->user->firstname}} {{$job->user->lastname}}</span>
    </p>
    <p>
        @include('maia::mixins.job-state', ['job' => $job])
    </p>
    @if ($job->hasFailed() && array_key_exists('message', $job->error))
        <div class="panel panel-danger">
            <div class="panel-body text-danger">{{$job->error['message']}}</div>
        </div>
    @endif
    <table class="table">
        <tbody>
            <tr>
                <td>Clusters</td>
                <td class="text-right"><code>{{array_get($job->params, 'clusters')}}</code></td>
            </tr>
            <tr>
                <td>Patch size</td>
                <td class="text-right"><code>{{array_get($job->params, 'patch_size')}}</code></td>
            </tr>
            <tr>
                <td>Threshold</td>
                <td class="text-right"><code>{{array_get($job->params, 'threshold')}}</code></td>
            </tr>
            <tr>
                <td>Latent size</td>
                <td class="text-right"><code>{{array_get($job->params, 'latent_size')}}</code></td>
            </tr>
            <tr>
                <td>Training size</td>
                <td class="text-right"><code>{{array_get($job->params, 'trainset_size')}}</code></td>
            </tr>
            <tr>
                <td>Training epochs</td>
                <td class="text-right"><code>{{array_get($job->params, 'epochs')}}</code></td>
            </tr>
        </tbody>
    </table>
    <form class="text-right" action="{{ url("api/v1/maia-jobs/{$job->id}") }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
        @if ($job->state_id === $states['novelty-detection'])
            <button class="btn btn-danger" type="button" title="The job cannot be deleted while the novelty detection is running" disabled>Delete this job</button>
        @elseif ($job->state_id === $states['instance-segmentation'])
            <button class="btn btn-danger" type="button" title="The job cannot be deleted while the instance segmentation is running" disabled>Delete this job</button>
        @else
            <button class="btn btn-danger" type="submit">Delete this job</button>
        @endif
    </form>
</div>
