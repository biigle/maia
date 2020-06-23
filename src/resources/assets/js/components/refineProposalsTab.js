/**
 * The refine training proposals tab
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineProposalsTab', {
    props: {
        selectedProposals: {
            type: Array,
            required: true,
        },
        seenProposals: {
            type: Array,
            required: true,
        },
    },
    computed: {
        numberSelectedProposals() {
            return this.selectedProposals.length;
        },
        numberSeenProposals() {
            return this.seenProposals.length;
        },
        hasNoSelectedProposals() {
            return this.numberSelectedProposals === 0;
        },
        hasSeenAllSelectedProposals() {
            return this.numberSelectedProposals > 0 && this.numberSelectedProposals === this.numberSeenProposals;
        },
        textClass() {
            return this.hasSeenAllSelectedProposals ? 'text-success' : '';
        },
        buttonClass() {
            return this.hasSeenAllSelectedProposals ? 'btn-success' : 'btn-default';
        },
    },
});
