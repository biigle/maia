<refine-tp-tab v-on:proceed="startInstanceSegmentation" v-bind:training-proposals="trainingProposals" inline-template>
<div class="sidebar-tab__content">
    @if ($job->state_id === $states['training-proposals'])
        <div class="panel panel-info">
            <div class="panel-body text-info">
                Please modify each training proposal that was marked as interesting, so that it fully encloses the interesting object or region of the image. Then submit the training proposals to continue with MAIA.
            </div>
        </div>
    @else
        <div class="panel panel-default">
            <div class="panel-body">
                The training proposals have been submitted and can no longer be edited.
            </div>
        </div>
    @endif

    @if ($job->state_id === $states['training-proposals'])
        <div class="text-right">
            <button class="btn btn-success" v-on:click="proceed" :disabled="hasNoSelectedTp">Submit training proposals</button>
        </div>
    @endif
</div>
</refine-tp-tab>
