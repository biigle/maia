<candidates-image-grid
    :converted-candidate-ids="convertedCandidateIds"
    :height="{{config('thumbnails.height')}}"
    :images="sortedCandidates"
    :pinned-image="referenceCandidate"
    :selectable="isInAnnotationCandidateState"
    :selected-candidate-ids="selectedCandidateIds"
    :pinnable="isInAnnotationCandidateState"
    :width="{{config('thumbnails.width')}}"
    empty-url="{{ asset(config('thumbnails.empty_url')) }}"
    listener-set="select-candidates"
    ref="candidatesImageGrid"
    selected-icon="check"
    v-on:pin="handleSelectedReferenceCandidate"
    v-on:select="handleSelectedCandidate"
    v-show="hasCandidates"
    ></candidates-image-grid>
<div v-if="!loading && !hasCandidates" class="maia-content-message">
    <div class="text-warning">
        There are no annotation candidates.
    </div>
</div>
