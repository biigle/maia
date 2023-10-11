<proposals-image-grid
    :height="{{config('thumbnails.height')}}"
    :images="proposalsForSelectView"
    :pinned-image="referenceProposal"
    :selectable="isInTrainingProposalState"
    :selected-proposal-ids="selectedProposalIds"
    :pinnable="isInTrainingProposalState"
    :width="{{config('thumbnails.width')}}"
    empty-url="{{ asset(config('thumbnails.empty_url')) }}"
    listener-set="select-proposals"
    ref="proposalsImageGrid"
    selected-icon="plus"
    v-on:pin="handleSelectedReferenceProposal"
    v-on:select="handleSelectedProposal"
    v-show="hasProposals"
    ></proposals-image-grid>
<div v-if="!loading && !hasProposals" class="maia-content-message">
    <div class="text-warning">
        There are no training proposals.
    </div>
</div>
