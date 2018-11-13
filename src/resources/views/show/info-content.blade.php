<div class="maia-content-message">
    @if ($job->state_id === $states['annotation-candidates'])
        <div class="maia-status">
            <span class="fa-stack fa-2x" title="Job finished">
                <i class="fas fa-circle fa-stack-2x"></i>
                <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
            </span>
        </div>
        <p class="text-success">
            This job has been finished. You can review the annotation candidates <i class="fas fa-check-square"></i> to create the final annotations.
        </p>
    @else
        <div class="maia-status maia-status--running">
            <span class="fa-stack fa-2x" title="Job in progress">
                <i class="fas fa-circle fa-stack-2x"></i>
                <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
            </span>
        </div>
        @if ($job->state_id === $states['novelty-detection'])
            <p class="text-warning">
                Novelty detection in progress. Please come back later.
            </p>
        @elseif ($job->state_id === $states['training-proposals'])
            <p class="text-warning">
                Please select <i class="fas fa-plus-square"></i> and refine <i class="fas fa-pen-square"></i> the training proposals.
            </p>
        @elseif ($job->state_id === $states['instance-segmentation'])
            <p class="text-warning">
                Instance segmentation in progress. Please come back later.
            </p>
        @endif
    @endif
</div>
