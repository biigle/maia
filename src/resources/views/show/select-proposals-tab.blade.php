<select-proposals-tab v-bind:proposals="proposals" v-bind:selected-proposals="selectedProposals" v-on:proceed="openRefineProposalsTab" inline-template>
<div class="sidebar-tab__content">
    @if ($job->state_id === $states['training-proposals'])
        <div class="panel panel-info">
            <div class="panel-body text-info">
                Select the training proposals that show (part of) an interesting object or region of the image. Then proceed to the refinement of the training proposals.
            </div>
        </div>
        <p>
            <span v-text="selectedProposalsCount">0</span> of <span v-text="proposalsCount">0</span> training proposals selected.
        </p>
    @else
        <div class="panel panel-default">
            <div class="panel-body">
                The training proposals have been submitted and can no longer be edited.
            </div>
        </div>
        <p>
            Only the <span v-text="selectedProposalsCount">0</span> selected of the <span v-text="proposalsCount">0</span> training proposals are shown.
        </p>
    @endif


    @if ($job->state_id === $states['training-proposals'])
        <div class="text-right">
            <button class="btn btn-default btn-block" v-on:click="proceed">Proceed</button>
        </div>
    @endif
</div>
</select-proposals-tab>
