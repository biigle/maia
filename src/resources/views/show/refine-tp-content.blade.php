<refine-tp-canvas
    :can-modify="@if ($job->state_id === $states['training-proposals']) true @else false @endif"
    :show-minimap="hasCurrentImage"
    :image="currentImage"
    :annotations="currentSelectedTrainingProposals"
    :unselected-annotations="currentUnselectedTrainingProposals"
    :selected-annotations="focussedTrainingProposalArray"
    v-on:previous-image="handlePreviousImage"
    v-on:previous="handlePrevious"
    v-on:next="handleNext"
    v-on:next-image="handleNextImage"
    v-on:update="handleRefineTp"
    v-on:select-tp="selectTrainingProposal"
    v-on:unselect-tp="unselectTrainingProposal"
    listener-set="refine-tp"
    ref="refineCanvas"
    inline-template>
    <div class="annotation-canvas">
        <minimap v-show="showMinimap" :extent="extent" :projection="projection" inline-template>
            <div class="annotation-canvas__minimap"></div>
        </minimap>
        <div class="annotation-canvas__toolbar">
            <div v-if="hasAnnotations" class="btn-group">
                <control-button icon="fa-step-backward" title="Previous training proposal 𝗟𝗲𝗳𝘁 𝗮𝗿𝗿𝗼𝘄" v-on:click="handlePrevious"></control-button>
                <control-button icon="fa-step-forward" title="Next training proposal 𝗥𝗶𝗴𝗵𝘁 𝗮𝗿𝗿𝗼𝘄/𝗦𝗽𝗮𝗰𝗲" v-on:click="handleNext"></control-button>
            </div>
            <div v-else class="btn-group">
                <control-button icon="fa-step-backward" title="Previous image 𝗟𝗲𝗳𝘁 𝗮𝗿𝗿𝗼𝘄" v-on:click="handlePreviousImage"></control-button>
                <control-button icon="fa-step-forward" title="Next image 𝗥𝗶𝗴𝗵𝘁 𝗮𝗿𝗿𝗼𝘄/𝗦𝗽𝗮𝗰𝗲" v-on:click="handleNextImage"></control-button>
            </div>
            @if ($job->state_id === $states['training-proposals'])
                <div class="btn-group drawing-controls">
                    <control-button icon="fa-plus" title="Mark a training proposal as interesting" :active="selectingTp" v-on:click="toggleMarkAsInteresting"></control-button>
                </div>
            @endif
        </div>
    </div>
</refine-tp-canvas>
