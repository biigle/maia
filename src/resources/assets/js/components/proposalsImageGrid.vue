<template>
    <div class="image-grid" @wheel.prevent="scroll">
        <div class="image-grid__images" ref="images">
            <image-grid-image
                :empty-url="emptyUrl"
                :image="pinnedImage"
                :is-pinned="true"
                :key="pinnedImage.id"
                :pinnable="pinnable"
                :selectable="selectable"
                :selected-fade="false"
                :selected-icon="selectedIcon"
                :small-icon="true"
                :selected-proposal-ids="selectedProposalIds"
                v-if="hasPinnedImage"
                @select="emitSelect"
                @pin="emitPin"
                ></image-grid-image>
            <image-grid-image
                v-for="image in displayedImages"
                :empty-url="emptyUrl"
                :image="image"
                :key="image.id"
                :pinnable="pinnable"
                :selectable="selectable"
                :selected-fade="selectable"
                :selected-icon="selectedIcon"
                :small-icon="!selectable"
                :selected-proposal-ids="selectedProposalIds"
                @select="emitSelect"
                @pin="emitPin"
                ></image-grid-image>
        </div>
        <image-grid-progress
            v-if="canScroll"
            :progress="progress"
            @top="jumpToStart"
            @prev-page="reversePage"
            @prev-row="reverseRow"
            @jump="jumpToPercent"
            @next-row="advanceRow"
            @next-page="advancePage"
            @bottom="jumpToEnd"
            ></image-grid-progress>
    </div>
</template>

<script>
import Image from './proposalsImageGridImage.vue';
import {ImageGrid} from '../import.js';

/**
 * A variant of the image grid used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
export default {
    extends: ImageGrid,
    components: {
        imageGridImage: Image,
    },
    props: {
        selectedProposalIds: {
            type: Object,
            required: true,
        },
    },
};
</script>
