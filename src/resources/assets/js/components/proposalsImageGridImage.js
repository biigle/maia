/**
 * A letiant of the image grid image used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.proposalsImageGridImage', {
    mixins: [
        biigle.$require('volumes.components.imageGridImage'),
        biigle.$require('largo.mixins.annotationPatch'),
    ],
    template: '<figure class="image-grid__image" :class="classObject" :title="title">' +
        '<div v-if="showIcon" class="image-icon">' +
            '<i class="fas" :class="iconClass"></i>' +
        '</div>' +
        '<img @click="toggleSelect" :src="url || emptyUrl" @error="showEmptyImage">' +
        '<div v-if="showAnnotationLink" class="image-buttons">' +
            '<a :href="showAnnotationLink" target="_blank" class="image-button" title="Show the annotation in the annotation tool">' +
                '<span class="fa fa-external-link-square-alt" aria-hidden="true"></span>' +
            '</a>' +
        '</div>' +
    '</figure>',
    computed: {
        showAnnotationLink() {
            return false;
        },
        selected() {
            return this.$parent.selectedProposalIds.hasOwnProperty(this.image.id);
        },
        title() {
            if (this.selectable) {
                return this.selected ? 'Unselect as interesting' : 'Select as interesting';
            }
        },
        // Show the small icon when the job is no longer in training proposal state.
        smallIcon() {
            return !this.selectable;
        },
        // Do not fade selected images when the job is no longer in training proposal
        // state.
        selectedFade() {
            return this.selectable;
        },
        id() {
            return this.image.id;
        },
        uuid() {
            return this.image.uuid;
        },
        urlTemplate() {
            return biigle.$require('maia.tpUrlTemplate');
        },
    },
});
