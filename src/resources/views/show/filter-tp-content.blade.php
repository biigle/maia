<div v-if="filterTpTabOpen" v-cloak class="maia-content">
    <tp-image-grid :images="trainingProposals" empty-url="{{ asset(config('thumbnails.empty_url')) }}" :width="{{config('thumbnails.width')}}" :height="{{config('thumbnails.height')}}" :initial-offset="filterTpOffset" v-on:select="handleSelectedTrainingProposal" v-on:deselect="handleDeselectedTrainingProposal" v-on:scroll="updateFilterTpOffset"></tp-image-grid>
    <div class="largo-images__alerts" :class="{block: loading}">
        <div v-if="loading">
            <loader :active="true" :fancy="true"></loader>
        </div>
        <div v-else v-if="hasNoTrainingProposals" class="panel panel-warning">
            <div class="panel-body text-warning">
                There are no training proposals.
            </div>
        </div>
    </div>
</div>
