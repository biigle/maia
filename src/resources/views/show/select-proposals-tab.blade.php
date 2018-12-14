<select-proposals-tab v-bind:proposals="proposals" v-bind:selected-proposals="selectedProposals" v-on:proceed="openRefineProposalsTab" inline-template>
<div class="sidebar-tab__content sidebar-tab__content--maia">
    <div class="maia-tab-content__top">
        <p class="lead">
            <span v-text="selectedProposalsCount">0</span> of <span v-text="proposalsCount">0</span> selected
        </p>
        @if ($job->state_id === $states['training-proposals'])
            <p>
                The quality of annotation candidates directly depends on the number of selected training proposals. In some cases a few hundred may be sufficient, in other cases many more might be required.
            </p>
        @else
            <div class="panel panel-default">
                <div class="panel-body">
                    The training proposals have been submitted and can no longer be edited.
                </div>
            </div>
            <p class="text-muted">
                Only selected training proposals are shown.
            </p>
        @endif
    </div>
    <div class="maia-tab-content__bottom">
        @if ($job->state_id === $states['training-proposals'])
            <div class="panel panel-info">
                <div class="panel-body text-info">
                    Select the training proposals that show (part of) an interesting object or region of the image. Then proceed to the refinement of the training proposals.
                </div>
            </div>
            <button class="btn btn-default btn-block" v-on:click="proceed">Proceed</button>
        @endif
    </div>
</div>
</select-proposals-tab>
