<refine-tp-canvas
    :can-modify="@if ($job->state_id === $states['training-proposals']) true @else false @endif"
    :show-minimap="hasCurrentImage"
    :image="currentImage"
    :annotations="selectedTpForCurrentImage"
    :unselected-annotations="unSelectedTpForCurrentImage"
    :selected-annotations="currentTpArray"
    {{-- :last-created-annotation="lastCreatedAnnotation" --}}
    v-on:previous="handlePrevious"
    v-on:next="handleNext"
    {{-- v-on:select="handleSelectAnnotations" --}}
    v-on:update="handleRefineTp"
    {{-- v-on:delete="handleDeleteAnnotations" --}}
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
            {{-- <div class="btn-group drawing-controls">
                <control-button icon="fa-plus" title="Toggle marking of a training proposal as interesting" :active="false" v-on:click="toggleMarkAsInteresting" disabled></control-button>
            </div> --}}
        </div>
    </div>
</refine-tp-canvas>
<div v-if="!loading && hasNoSelectedTp" class="maia-content-message">
    <div class="text-warning">
        Please select <i class="fas fa-plus-square"></i> training proposals.
    </div>
</div>
