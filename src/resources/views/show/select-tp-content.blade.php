<tp-image-grid
    v-show="hasTrainingProposals"
    :images="trainingProposals"
    empty-url="{{ asset(config('thumbnails.empty_url')) }}"
    :width="{{config('thumbnails.width')}}"
    :height="{{config('thumbnails.height')}}"
    :initial-offset="selectTpOffset"
    :selectable="isInTrainingProposalState"
    selected-icon="plus"
    v-on:select="handleSelectedTrainingProposal"
    v-on:scroll="updateSelectTpOffset"
    ref="imageGrid"></tp-image-grid>
<div v-if="!loading && !hasTrainingProposals" class="maia-content-message">
    <div class="text-warning">
        There are no training proposals.
    </div>
</div>
