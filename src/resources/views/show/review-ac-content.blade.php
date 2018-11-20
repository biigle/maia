<ac-image-grid
    v-show="hasAnnotationCandidates"
    :images="annotationCandidates"
    empty-url="{{ asset(config('thumbnails.empty_url')) }}"
    :width="{{config('thumbnails.width')}}"
    :height="{{config('thumbnails.height')}}"
    :initial-offset="reviewAcOffset"
    :selectable="isInAnnotationCandidateState"
    selected-icon="check"
    v-on:select="handleSelectedAnnotationCandidate"
    v-on:scroll="updateReviewAcOffset"></ac-image-grid>
<div v-if="!loading && !hasAnnotationCandidates" class="maia-content-message">
    <div class="text-warning">
        There are no annotation candidates.
    </div>
</div>
