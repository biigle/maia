/**
 * A variant of the image grid image used for the selection of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.tpImageGridImage', {
    mixins: [biigle.$require('volumes.components.imageGridImage')],
    template: '<figure class="image-grid__image" :class="classObject" :title="title">' +
        '<div v-if="showIcon" class="image-icon">' +
            '<i class="fas fa-3x" :class="iconClass"></i>' +
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
            return this.$parent.selectedTpIds.hasOwnProperty(this.image.id);
        },
        title: function () {
            if (this.selectable) {
                return this.selected ? 'Unselect as interesting' : 'Select as interesting';
            }
        },
    },
    methods: {
        getBlob: function () {
            return biigle.$require('maia.api.maiaAnnotation').getFile({id: this.image.id});
        },
    },
});
