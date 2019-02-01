<refine-candidates-canvas
    :can-modify="isInAnnotationCandidateState"
    :show-minimap="hasCurrentCandidateImage"
    :image="currentCandidateImage"
    :annotations="currentSelectedCandidates"
    :unselected-annotations="currentUnselectedCandidates"
    :converted-annotations="currentConvertedCandidates"
    :selected-annotations="focussedCandidateArray"
    v-on:previous-image="handlePreviousCandidateImage"
    v-on:previous="handlePreviousCandidate"
    v-on:next="handleNextCandidate"
    v-on:next-image="handleNextCandidateImage"
    v-on:update="handleRefineCandidate"
    v-on:select="selectCandidate"
    v-on:unselect="unselectCandidate"
    listener-set="refine-candidates"
    ref="refineCandidatesCanvas"
    inline-template>
    <div class="annotation-canvas">
        <minimap v-show="showMinimap" :extent="extent"></minimap>
        <div class="annotation-canvas__toolbar">
            <div v-if="hasAnnotations" class="btn-group">
                <control-button icon="fa-step-backward" title="Previous annotationcandidate 𝗟𝗲𝗳𝘁 𝗮𝗿𝗿𝗼𝘄" v-on:click="handlePrevious"></control-button>
                <control-button icon="fa-step-forward" title="Next annotation candidate 𝗥𝗶𝗴𝗵𝘁 𝗮𝗿𝗿𝗼𝘄/𝗦𝗽𝗮𝗰𝗲" v-on:click="handleNext"></control-button>
            </div>
            <div v-else class="btn-group">
                <control-button icon="fa-step-backward" title="Previous image 𝗟𝗲𝗳𝘁 𝗮𝗿𝗿𝗼𝘄" v-on:click="handlePreviousImage"></control-button>
                <control-button icon="fa-step-forward" title="Next image 𝗥𝗶𝗴𝗵𝘁 𝗮𝗿𝗿𝗼𝘄/𝗦𝗽𝗮𝗰𝗲" v-on:click="handleNextImage"></control-button>
            </div>
            <div class="btn-group drawing-controls">
                <control-button icon="fa-check" title="Attach a label to annotation candidates" :active="selectingMaiaAnnotation" v-on:click="toggleSelectingMaiaAnnotation"></control-button>
            </div>
        </div>
    </div>
</refine-candidates-canvas>
