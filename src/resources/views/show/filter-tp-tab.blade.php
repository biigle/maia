<filter-tp-tab v-bind:training-proposals="trainingProposals" v-on:proceed="openRefineTpTab" inline-template>
<div class="sidebar-tab__content">
    @if ($job->state_id === $states['training-proposals'])
        <div class="panel panel-info">
            <div class="panel-body text-info">
                Please select all training proposals which show (part of) an interesting object or region of the image. Then proceed to the refinement of the training proposals.
            </div>
        </div>
    @else
        <div class="panel panel-default">
            <div class="panel-body">
                The training proposals have been submitted and can no longer be edited.
            </div>
        </div>
    @endif

    <p>
        <span v-text="selectedTpCount">0</span> of <span v-text="tpCount">0</span> training proposals selected.
    </p>

    @if ($job->state_id === $states['training-proposals'])
        <div class="text-right">
            <button class="btn btn-success" v-on:click="proceed">Proceed</button>
        </div>
    @endif
</div>
</filter-tp-tab>
