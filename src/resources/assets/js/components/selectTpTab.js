/**
 * The select training proposals tab
 *
 * @type {Object}
 */
biigle.$component('maia.components.selectTpTab', {
    props: {
        trainingProposals: {
            type: Array,
            required: true,
        },
        selectedTrainingProposals: {
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
        selectedTpCount: function () {
            return this.selectedTrainingProposals.length;
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
