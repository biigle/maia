<template>
    <figure
        class="image-grid__image"
        :class="classObject"
        :title="title"
        >
        <div v-if="showIcon" class="image-icon">
            <i class="fas" :class="iconClass"></i>
        </div>
        <img @click="toggleSelect" :src="srcUrl" @error="showEmptyImage">
        <div
            v-if="pinnable"
            class="image-buttons"
            >
            <button
                class="image-button image-button__pin"
                :title="pinButtonTitle"
                @click="emitPin"
                >
                <span class="fa fa-thumbtack fa-fw"></span>
            </button>
        </div>
    </figure>
</template>

<script>
import {AnnotationPatch} from '../import.js';
import {ImageGridImage} from '../import.js';

/**
 * A variant of the image grid image used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
export default {
    mixins: [
        ImageGridImage,
        AnnotationPatch,
    ],
    props: {
        selectedProposalIds: {
            type: Object,
            required: true,
        },
    },
    computed: {
        selected() {
            return this.selectedProposalIds.hasOwnProperty(this.image.id);
        },
        title() {
            if (this.selectable) {
                return this.selected ? 'Unselect as interesting' : 'Select as interesting';
            }

            return '';
        },
        id() {
            return this.image.id;
        },
        uuid() {
            return this.image.uuid;
        },
        urlTemplate() {
            // Usually this would be set in the created function but in this special
            // case this is not possible.
            return biigle.$require('maia.tpUrlTemplate');
        },
        pinButtonTitle() {
            if (this.isPinned) {
                return 'Unselect reference';
            }

            return 'Select as reference (sort by similarity)';
        },
    },
};
</script>
