<template>
    <div class="image-grid" @wheel.prevent="scroll">
        <div class="image-grid__images" ref="images">
            <image-grid-image
                :empty-url="emptyUrl"
                :image="pinnedImage"
                :is-pinned="true"
                :key="pinnedImage.id"
                :pinnable="pinnable"
                :selectable="!isConverted(pinnedImage)"
                :selected-fade="false"
                :selected-icon="selectedIcon"
                :small-icon="true"
                :selected-candidate-ids="selectedCandidateIds"
                :converted-candidate-ids="convertedCandidateIds"
                v-if="hasPinnedImage"
                @select="emitSelect"
                @pin="emitPin"
                ></image-grid-image>
            <image-grid-image
                v-for="image in displayedImages"
                :key="image.id"
                :pinnable="pinnable"
                :image="image"
                :empty-url="emptyUrl"
                :selectable="!isConverted(image)"
                :selected-icon="selectedIcon"
                :selected-candidate-ids="selectedCandidateIds"
                :converted-candidate-ids="convertedCandidateIds"
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
import Image from './candidatesImageGridImage';
import {ImageGrid} from '../import';

/**
 * A variant of the image grid used for the selection of MAIA annotation candidates.
 *
 * @type {Object}
 */
export default {
    mixins: [ImageGrid],
    components: {
        imageGridImage: Image,
    },
    props: {
        selectedCandidateIds: {
            type: Object,
            required: true,
        },
        convertedCandidateIds: {
            type: Object,
            required: true,
        },
    },
    methods: {
        isConverted(image) {
            // image may be null in case of the pinnedImage.
            return this.convertedCandidateIds.hasOwnProperty(image?.id);
        },
    },
};
</script>
