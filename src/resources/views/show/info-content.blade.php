<div class="maia-content-message">
    @if ($job->state_id === $states['annotation-candidates'])
        <div class="maia-status">
            <span class="fa-stack fa-2x" title="Job finished">
                <i class="fas fa-circle fa-stack-2x"></i>
                <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
            </span>
        </div>
        <p class="text-success text-center lead">
            This job is finished.<br>Review the annotation candidates <i class="fas fa-check-square"></i> to create the final annotations.
        </p>
    @elseif ($job->hasFailed())
        <div class="maia-status maia-status--failed">
            <span class="fa-stack fa-2x" title="Job failed">
                <i class="fas fa-circle fa-stack-2x"></i>
                <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
            </span>
        </div>
        <p class="text-danger lead">
            @if ($job->state_id === $states['failed-novelty-detection'])
                The job has failed during novelty detection.
            @else
                The job has failed during instance segmentation.
            @endif
        </p>
        @if (config('app.debug') && array_key_exists('message', $job->error))
            <pre style="max-width: 90%;max-height: 50%">{{$job->error['message']}}</pre>
        @endif
    @else
        <div class="maia-status maia-status--running">
            <span class="fa-stack fa-2x" title="Job in progress">
                @if ($job->isRunning())
                    <i class="fas fa-circle fa-stack-1x"></i>
                    <i class="fas fa-cog fa-spin fa-stack-2x"></i>
                @else
                    <i class="fas fa-circle fa-stack-2x"></i>
                @endif
                <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
            </span>
        </div>
        @if ($job->state_id === $states['novelty-detection'])
            <p class="text-warning text-center lead">
                Novelty detection in progress.<br>Please come back later.
            </p>
        @elseif ($job->state_id === $states['training-proposals'])
            <p class="text-warning lead">
                Please select <i class="fas fa-plus-square"></i> and refine <i class="fas fa-pen-square"></i> the training proposals.
            </p>
        @elseif ($job->state_id === $states['instance-segmentation'])
            <p class="text-warning text-center lead">
                Instance segmentation in progress.<br>Please come back later.
            </p>
        @endif
    @endif
</div>
