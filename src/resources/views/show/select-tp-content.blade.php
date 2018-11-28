<tp-image-grid
    v-show="hasTrainingProposals"
    :images="trainingProposals"
    :selected-tp-ids="selectedTrainingProposalIds"
    empty-url="{{ asset(config('thumbnails.empty_url')) }}"
    :width="{{config('thumbnails.width')}}"
    :height="{{config('thumbnails.height')}}"
    :selectable="isInTrainingProposalState"
    selected-icon="plus"
    listener-set="select-tp"
    v-on:select="handleSelectedTrainingProposal"
    ref="imageGrid"></tp-image-grid>
<div v-if="!loading && !hasTrainingProposals" class="maia-content-message">
    <div class="text-warning">
        There are no training proposals.
    </div>
</div>
