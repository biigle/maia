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
    data() {
        return {
            //
        };
    },
    computed: {
        selectedProposalsCount() {
            return this.selectedProposals.length;
        },
        proposalsCount() {
            return this.proposals.length;
        },
    },
    methods: {
        proceed() {
            this.$emit('proceed');
        },
    },
    created() {
        //
    },
});
