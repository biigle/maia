/**
 * The filter training proposals tab
 *
 * @type {Object}
 */
biigle.$component('maia.components.filterTpTab', {
    props: {
        trainingProposals: {
            type: Array,
            required: true,
        },
    },
    data: function () {
        return {
            //
        };
    },
    computed: {
        selectedTp: function () {
            return this.trainingProposals.filter(function (annotation) {
                return annotation.selected;
            });
        },
        selectedTpCount: function () {
            return this.selectedTp.length;
        },
        tpCount: function () {
            return this.trainingProposals.length;
        },
    },
    methods: {
        proceed: function () {
            this.$emit('proceed');
        },
    },
    created: function () {
        //
    },
});
