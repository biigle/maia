<div class="sidebar-tab__content">
    <p>
        Job #{{$job->id}} created <span title="{{$job->created_at->toIso8601String()}}">{{$job->created_at->diffForHumans()}}</span>
    </p>
    <p>
        Status: @include('maia::mixins.job-state', ['job' => $job])
    </p>
    <table class="table">
        <tbody>
            <tr>
                <td>Clusters</td>
                <td><code>{{$job->params['clusters']}}</code></td>
            </tr>
            <tr>
                <td>Patch size</td>
                <td><code>{{$job->params['patch_size']}}</code></td>
            </tr>
            <tr>
                <td>Threshold</td>
                <td><code>{{$job->params['threshold']}}</code></td>
            </tr>
            <tr>
                <td>Latent size</td>
                <td><code>{{$job->params['latent_size']}}</code></td>
            </tr>
            <tr>
                <td>Training size</td>
                <td><code>{{$job->params['trainset_size']}}</code></td>
            </tr>
            <tr>
                <td>Training epochs</td>
                <td><code>{{$job->params['epochs']}}</code></td>
            </tr>
        </tbody>
    </table>
</div>
