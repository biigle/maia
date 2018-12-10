/**
 * A variant of the image grid image used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.proposalsImageGridImage', {
    mixins: [biigle.$require('volumes.components.imageGridImage')],
    template: '<figure class="image-grid__image" :class="classObject" :title="title">' +
        '<div v-if="showIcon" class="image-icon">' +
            '<i class="fas" :class="iconClass"></i>' +
        '</div>' +
        '<img @click="toggleSelect" :src="url || emptyUrl">' +
        '<div v-if="showAnnotationLink" class="image-buttons">' +
            '<a :href="showAnnotationLink" target="_blank" class="image-button" title="Show the annotation in the annotation tool">' +
                '<span class="fa fa-external-link-square-alt" aria-hidden="true"></span>' +
            '</a>' +
        '</div>' +
    '</figure>',
    computed: {
        showAnnotationLink: function () {
            return false;
        },
        selected: function () {
            return this.$parent.selectedProposalIds.hasOwnProperty(this.image.id);
        },
        title: function () {
            if (this.selectable) {
                return this.selected ? 'Unselect as interesting' : 'Select as interesting';
            }
        },
        // Show the small icon when the job is no longer in training proposal state.
        smallIcon: function () {
            return !this.selectable;
        },
        // Do not fade selected images when the job is no longer in training proposal
        // state.
        selectedFade: function () {
            return this.selectable;
        },
    },
    methods: {
        getBlob: function () {
            return biigle.$require('maia.api.trainingProposal').getFile({id: this.image.id});
        },
    },
});
