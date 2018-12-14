<refine-proposals-tab v-bind:selected-proposals="selectedProposals" v-bind:seen-proposals="selectedAndSeenProposals" inline-template>
<div class="sidebar-tab__content sidebar-tab__content--maia">
    <div class="maia-tab-content__top">
        @if ($job->state_id === $states['training-proposals'])
            <p class="lead" :class="textClass">
                <span v-text="numberSeenProposals">0</span> of <span v-text="numberSelectedProposals">0</span> seen
            </p>
            <p>
                The quality of annotation candidates directly depends on the number of selected training proposals. In some cases a few hundred may be sufficient, in other cases many more might be required.
            </p>
        @else
            <div class="panel panel-default">
                <div class="panel-body">
                    The training proposals have been submitted and can no longer be edited.
                </div>
            </div>
        @endif
    </div>
    <div class="maia-tab-content__bottom">
        @if ($job->state_id === $states['training-proposals'])
            <div v-if="hasNoSelectedProposals" class="panel panel-warning">
                <div class="panel-body text-warning">
                    Please select <i class="fas fa-plus-square"></i> training proposals.
                </div>
            </div>
            <div v-else v-cloak class="panel panel-info">
                <div class="panel-body text-info">
                    Modify each selected training proposal, so that it fully encloses the interesting object or region of the image. Then submit the training proposals to continue with MAIA.
                </div>
            </div>

            <form action="{{url("api/v1/maia-jobs/{$job->id}/training-proposals")}}" method="POST" onsubmit="return confirm('Once the training proposals have been submitted, you are no longer able to modify them. Continue?')">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-block" :class="buttonClass" :disabled="hasNoSelectedProposals">Submit training proposals</button>
            </form>
        @endif
    </div>
</div>
</refine-proposals-tab>
