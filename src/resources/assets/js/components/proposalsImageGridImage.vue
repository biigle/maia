<template>
    <figure class="image-grid__image" :class="classObject" :title="title">
        <div v-if="showIcon" class="image-icon">
            <i class="fas" :class="iconClass"></i>
        </div>
        <img @click="toggleSelect" :src="url || emptyUrl" @error="showEmptyImage">
    </figure>
</template>

<script>
import {AnnotationPatch} from '../import';
import {ImageGridImage} from '../import';

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
    computed: {
        selected() {
            return this.$parent.selectedProposalIds.hasOwnProperty(this.image.id);
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
    },
};
</script>

