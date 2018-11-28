<ac-image-grid
    v-show="hasAnnotationCandidates"
    :images="annotationCandidates"
    empty-url="{{ asset(config('thumbnails.empty_url')) }}"
    :width="{{config('thumbnails.width')}}"
    :height="{{config('thumbnails.height')}}"
    :selectable="isInAnnotationCandidateState"
    selected-icon="check"
    listener-set="review-ac"
    v-on:select="handleSelectedAnnotationCandidate"></ac-image-grid>
<div v-if="!loading && !hasAnnotationCandidates" class="maia-content-message">
    <div class="text-warning">
        There are no annotation candidates.
    </div>
</div>
