import Image from './proposalsImageGridImage';
import {ImageGrid} from '../import';

/**
 * A variant of the image grid used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
export default {
    mixins: [ImageGrid],
    template: `<div class="image-grid" @wheel.prevent="scroll">
        <div class="image-grid__images" ref="images">
            <image-grid-image v-for="image in displayedImages" :key="image.id" :image="image" :empty-url="emptyUrl" :selectable="selectable" :selected-fade="selectable" :small-icon="!selectable" :selected-icon="selectedIcon" @select="emitSelect"></image-grid-image>
        </div>
        <image-grid-progress v-if="canScroll" :progress="progress" @top="jumpToStart" @prev-page="reversePage" @prev-row="reverseRow" @jump="jumpToPercent" @next-row="advanceRow" @next-page="advancePage" @bottom="jumpToEnd"></image-grid-progress>
    </div>`,
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
