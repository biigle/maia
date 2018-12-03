<div class="sidebar-tab__content">
    <p>
        Job #{{$job->id}} created <span title="{{$job->created_at->toIso8601String()}}">{{$job->created_at->diffForHumans()}}<br>by {{$job->user->firstname}} {{$job->user->lastname}}</span>
    </p>
    <p>
        @include('maia::mixins.job-state', ['job' => $job])
    </p>
    <table class="table">
        <thead>
            <tr colspan="2">
                <th>Novelty Detection</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Clusters</td>
                <td class="text-right"><code>{{array_get($job->params, 'nd_clusters')}}</code></td>
            </tr>
            <tr>
                <td>Patch size</td>
                <td class="text-right"><code>{{array_get($job->params, 'nd_patch_size')}}</code></td>
            </tr>
            <tr>
                <td>Threshold</td>
                <td class="text-right"><code>{{array_get($job->params, 'nd_threshold')}}</code></td>
            </tr>
            <tr>
                <td>Latent size</td>
                <td class="text-right"><code>{{array_get($job->params, 'nd_latent_size')}}</code></td>
            </tr>
            <tr>
                <td>Training size</td>
                <td class="text-right"><code>{{array_get($job->params, 'nd_trainset_size')}}</code></td>
            </tr>
            <tr>
                <td>Training epochs</td>
                <td class="text-right"><code>{{array_get($job->params, 'nd_epochs')}}</code></td>
            </tr>
            <tr>
                <td>Stride</td>
                <td class="text-right"><code>{{array_get($job->params, 'nd_stride')}}</code></td>
            </tr>
            <tr>
                <td>Ignore radius</td>
                <td class="text-right"><code>{{array_get($job->params, 'nd_ignore_radius')}}</code></td>
            </tr>
        </tbody>
    </table>
    <table class="table">
        <thead>
            <tr colspan="2">
                <th>Instance Segmentation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Training epochs (head)</td>
                <td class="text-right"><code>{{array_get($job->params, 'is_epochs_head')}}</code></td>
            </tr>
            <tr>
                <td>Training epochs (all)</td>
                <td class="text-right"><code>{{array_get($job->params, 'is_epochs_all')}}</code></td>
            </tr>
        </tbody>
    </table>
    <form class="text-right" action="{{ url("api/v1/maia-jobs/{$job->id}") }}" method="POST" onsubmit="return confirm('Are you sure that you want to delete this job?')">
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
