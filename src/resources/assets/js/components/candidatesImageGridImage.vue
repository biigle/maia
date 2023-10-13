<template>
    <figure
        class="image-grid__image image-grid__image--annotation-candidate"
        :class="customClassObject"
        :title="title"
        >
        <div v-if="showIcon" class="image-icon">
            <i class="fas" :class="iconClass"></i>
        </div>
        <img @click="toggleSelect" :src="srcUrl" @error="showEmptyImage">
        <div v-if="selected" class="attached-label">
            <span class="attached-label__color" :style="labelStyle"></span>
            <span class="attached-label__name" v-text="label.name"></span>
        </div>
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
import {AnnotationPatch} from '../import';
import {ImageGridImage} from '../import';

/**
 * A variant of the image grid image used for the selection of MAIA annotation candidates.
 *
 * @type {Object}
 */
export default {
    mixins: [
        ImageGridImage,
        AnnotationPatch,
    ],
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
    computed: {
        label() {
            if (this.selected) {
                return this.image.label;
            }

            return null;
        },
        selected() {
            return this.selectedCandidateIds.hasOwnProperty(this.image.id);
        },
        converted() {
            return this.convertedCandidateIds.hasOwnProperty(this.image.id);
        },
        customClassObject() {
            const obj = Object.assign({}, this.classObject);
            obj['image-grid__image--selected'] = this.selected || this.converted;

            return obj;
        },
        iconClass() {
            if (this.converted) {
                return 'fa-lock';
            }

            return 'fa-' + this.selectedIcon;
        },
        showIcon() {
            return this.selectable || this.selected || this.converted;
        },
        title() {
            if (this.converted) {
                return 'This annotation candidate has been converted';
            }

            return this.selected ? 'Detach label' : 'Attach selected label';
        },
        labelStyle() {
            return {
                'background-color': '#' + this.label.color,
            };
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
            return biigle.$require('maia.acUrlTemplate');
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
