<refine-proposals-canvas
    :can-modify="isInAnnotationCandidateState"
    :show-minimap="hasCurrentCandidateImage"
    :image="currentCandidateImage"
    {{-- :annotations="currentSelectedCandidates" --}}
    :unselected-annotations="currentUnselectedCandidates"
    {{-- :selected-annotations="focussedCandidateArray" --}}
    v-on:previous-image="handlePreviousCandidateImage"
    {{-- v-on:previous="handlePreviousCandidate" --}}
    {{-- v-on:next="handleNextCandidate" --}}
    v-on:next-image="handleNextCandidateImage"
    {{-- v-on:update="handleRefineCandidate" --}}
    {{-- v-on:select-tp="selectCandidate" --}}
    {{-- v-on:unselect-tp="unselectCandidate" --}}
    listener-set="refine-candidates"
    ref="refineCandidatesCanvas"
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
</refine-proposals-canvas>
