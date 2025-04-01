<refine-canvas
    :can-modify="isInTrainingProposalState"
    :show-minimap="hasCurrentProposalImage"
    :image="currentProposalImage"
    :annotations="currentSelectedProposals"
    :unselected-annotations="currentUnselectedProposals"
    :selected-annotations="focussedProposalArray"
    v-on:previous-image="handlePreviousProposalImage"
    v-on:previous="handlePreviousProposal"
    v-on:next="handleNextProposal"
    v-on:next-image="handleNextProposalImage"
    v-on:update="handleRefineProposal"
    v-on:select="selectProposal"
    v-on:unselect="unselectProposal"
    listener-set="refine-proposals"
    ref="refineProposalsCanvas"
    ></refine-canvas>

<script type="text/html" id="refine-proposals-canvas-template">
    <div class="annotation-canvas">
        <minimap v-show="showMinimap" :extent="extent"></minimap>
        <div class="annotation-canvas__toolbar">
            <div v-if="hasAnnotations" class="btn-group">
                <control-button icon="fa-step-backward" title="Previous training proposal 𝗟𝗲𝗳𝘁 𝗮𝗿𝗿𝗼𝘄" v-on:click="handlePrevious" :disabled="modifyInProgress"></control-button>
                <control-button icon="fa-step-forward" title="Next training proposal 𝗥𝗶𝗴𝗵𝘁 𝗮𝗿𝗿𝗼𝘄/𝗦𝗽𝗮𝗰𝗲" v-on:click="handleNext" :disabled="modifyInProgress"></control-button>
            </div>
            <div v-else class="btn-group">
                <control-button icon="fa-step-backward" title="Previous image 𝗟𝗲𝗳𝘁 𝗮𝗿𝗿𝗼𝘄" v-on:click="handlePreviousImage"></control-button>
                <control-button icon="fa-step-forward" title="Next image 𝗥𝗶𝗴𝗵𝘁 𝗮𝗿𝗿𝗼𝘄/𝗦𝗽𝗮𝗰𝗲" v-on:click="handleNextImage"></control-button>
            </div>
            @if ($job->state_id === $states['training-proposals'])
                <div class="btn-group drawing-controls">
                    <control-button icon="fa-minus" title="Unselect current training proposal as interesting 𝗗𝗲𝗹𝗲𝘁𝗲" :disabled="modifyInProgress || !hasSelectedAnnotations" v-on:click="handleUnselectMaiaAnnotation"></control-button>
                </div>
                <div class="btn-group drawing-controls">
                    <control-button icon="fa-plus" title="Select training proposals as interesting" :active="selectingMaiaAnnotation" v-on:click="toggleSelectingMaiaAnnotation"></control-button>
                </div>
            @endif
        </div>
    </div>
</script>
