<template>
    <figure class="image-grid__image image-grid__image--annotation-candidate" :class="classObject" :title="title">
        <div v-if="showIcon" class="image-icon">
            <i class="fas" :class="iconClass"></i>
        </div>
        <img @click="toggleSelect" :src="url || emptyUrl" @error="showEmptyImage">
        <div v-if="selected" class="attached-label">
            <span class="attached-label__color" :style="labelStyle"></span>
            <span class="attached-label__name" v-text="label.name"></span>
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
    computed: {
        label() {
            if (this.selected) {
                return this.image.label;
            }

            return null;
        },
        selected() {
            return this.$parent.isSelected(this.image);
        },
        converted() {
            return this.$parent.isConverted(this.image);
        },
        classObject() {
            return {
                'image-grid__image--selected': this.selected || this.converted,
                'image-grid__image--selectable': this.selectable,
                'image-grid__image--fade': this.selectedFade,
                'image-grid__image--small-icon': this.smallIcon,
            };
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
    },
};
</script>
