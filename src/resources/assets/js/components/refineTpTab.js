/**
 * The refine training proposals tab
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineTpTab', {
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
        hasNoSelectedTp: function () {
            return !this.trainingProposals.reduce(function (carry, current) {
                return carry || current.selected;
            }, false);
        },
    },
    methods: {
        //
    },
    created: function () {
        //
    },
});
