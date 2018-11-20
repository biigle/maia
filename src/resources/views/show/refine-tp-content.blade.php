<refine-tp-canvas
    :can-modify="@if ($job->state_id === $states['training-proposals']) true @else false @endif"
    :show-minimap="hasCurrentImage"
    :image="currentImage"
    :annotations="selectedTpForCurrentImage"
    :unselected-annotations="unSelectedTpForCurrentImage"
    :selected-annotations="currentTpArray"
    v-on:previous="handlePrevious"
    v-on:next="handleNext"
    v-on:update="handleRefineTp"
    v-on:select-tp="handleSelectTp"
    v-on:unselect-tp="handleUnselectTp"
    ref="refineCanvas"
    inline-template>
    <div class="annotation-canvas">
        <minimap v-show="showMinimap" :extent="extent" :projection="projection" inline-template>
            <div class="annotation-canvas__minimap"></div>
        </minimap>
        <div class="annotation-canvas__toolbar">
            <div class="btn-group">
                <control-button icon="fa-step-backward" title="Previous training proposal 𝗟𝗲𝗳𝘁 𝗮𝗿𝗿𝗼𝘄" v-on:click="handlePrevious"></control-button>
                <control-button icon="fa-step-forward" title="Next training proposal 𝗥𝗶𝗴𝗵𝘁 𝗮𝗿𝗿𝗼𝘄/𝗦𝗽𝗮𝗰𝗲" v-on:click="handleNext"></control-button>
            </div>
            @if ($job->state_id === $states['training-proposals'])
                <div class="btn-group drawing-controls">
                    <control-button icon="fa-plus" title="Mark a training proposal as interesting" :active="selectingTp" v-on:click="toggleMarkAsInteresting"></control-button>
                </div>
            @endif
        </div>
    </div>
</refine-tp-canvas>
<div v-if="!loading && hasNoSelectedTp" class="maia-content-message">
    <div class="text-warning">
        Please select <i class="fas fa-plus-square"></i> training proposals.
    </div>
</div>
