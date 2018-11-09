/**
 * A variant of the image grid image used for the filtering of MAIA training proposals.
 *
 * @type {Object}
 */
biigle.$component('maia.components.tpImageGridImage', {
    mixins: [biigle.$require('volumes.components.imageGridImage')],
    template: '<figure class="image-grid__image image-grid__image--relabel" :class="classObject" :title="title">' +
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
            // var route = biigle.$require('largo.showAnnotationRoute');
            // return route ? (route + this.image.id) : '';
        },
        selected: function () {
            return this.image.selected;
        },
        title: function () {
            return this.selected ? 'Undo marking as interesting' : 'Mark as interesting';
        },
    },
    methods: {
        getBlob: function () {
            return biigle.$require('maia.api.maiaAnnotation').getFile({id: this.image.id});
        },
    },
});
