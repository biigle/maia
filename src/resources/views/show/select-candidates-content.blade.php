<candidates-image-grid
    v-show="hasCandidates"
    :images="candidates"
    :selected-candidate-ids="selectedCandidateIds"
    empty-url="{{ asset(config('thumbnails.empty_url')) }}"
    :width="{{config('thumbnails.width')}}"
    :height="{{config('thumbnails.height')}}"
    :selectable="isInAnnotationCandidateState"
    selected-icon="check"
    listener-set="select-candidates"
    v-on:select="handleSelectedCandidate"
    ref="candidatesImageGrid"></candidates-image-grid>
<div v-if="!loading && !hasCandidates" class="maia-content-message">
    <div class="text-warning">
        There are no annotation candidates.
    </div>
</div>
