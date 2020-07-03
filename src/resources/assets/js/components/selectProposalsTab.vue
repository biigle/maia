<script>
/**
 * The select training proposals tab
 *
 * @type {Object}
 */
export default {
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
};
</script>
