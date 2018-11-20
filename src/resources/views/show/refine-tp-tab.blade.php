<refine-tp-tab v-bind:selected-training-proposals="selectedTpOrderedByImageId" v-bind:seen-training-proposals="selectedAndSeenTps" inline-template>
<div class="sidebar-tab__content">
    @if ($job->state_id === $states['training-proposals'])
        <div class="panel panel-info">
            <div class="panel-body text-info">
                Please modify each training proposal that was marked as interesting, so that it fully encloses the interesting object or region of the image. Then submit the training proposals to continue with MAIA.
            </div>
        </div>
        <p :class="textClass">
            Seen <span v-text="numberSeenTps">0</span> of <span v-text="numberSelectedTps">0</span> training proposals.
        </p>
    @else
        <div class="panel panel-default">
            <div class="panel-body">
                The training proposals have been submitted and can no longer be edited.
            </div>
        </div>
    @endif

    @if ($job->state_id === $states['training-proposals'])
        <div class="text-right">
            <form action="{{url("api/v1/maia-jobs/{$job->id}")}}" method="POST" onsubmit="return confirm('Once the training proposals have been submitted, you are no longer able to modify them. Continue?')">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">
                <button type="submit" class="btn btn-block" :class="buttonClass" :disabled="hasNoSelectedTp">Submit training proposals</button>
            </form>
        </div>
    @endif
</div>
</refine-tp-tab>
