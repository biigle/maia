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
import Image from './proposalsImageGridImage';
import {ImageGrid} from '../import';

/**
 * A variant of the image grid used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
export default {
    mixins: [ImageGrid],
    components: {
        imageGridImage: Image,
    },
    props: {
        selectedProposalIds: {
            type: Object,
            required: true,
        },
        pinnedImage: {
            type: Object,
            default: null,
        },
        pinnable: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        hasPinnedImage() {
            return this.pinnedImage !== null;
        },
        imagesOffsetEnd() {
            const offset = this.imagesOffset + this.columns * this.rows;
            if (this.hasPinnedImage) {
                return offset - 1;
            }

            return offset;
        },
        lastRow() {
            if (this.hasPinnedImage) {
                return Math.max(0, Math.ceil((this.images.length + 1) / this.columns) - this.rows);
            }

            return Math.max(0, Math.ceil(this.images.length / this.columns) - this.rows);
        },
    },
    methods: {
        emitPin(id) {
            this.$emit('pin', id);
        },
    },
    watch: {
        pinnedImage() {
            this.jumpToStart();
        },
    },
};
</script>
