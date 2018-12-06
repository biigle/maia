/**
 * The select training proposals tab
 *
 * @type {Object}
 */
biigle.$component('maia.components.selectProposalsTab', {
    props: {
        proposals: {
            type: Array,
            required: true,
        },
        selectedProposals: {
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
        selectedProposalsCount: function () {
            return this.selectedProposals.length;
        },
        proposalsCount: function () {
            return this.proposals.length;
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
