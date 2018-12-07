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
        numberSelectedProposals: function () {
            return this.selectedProposals.length;
        },
        numberSeenProposals: function () {
            return this.seenProposals.length;
        },
        hasNoSelectedProposals: function () {
            return this.numberSelectedProposals === 0;
        },
        hasSeenAllSelectedProposals: function () {
            return this.numberSelectedProposals > 0 && this.numberSelectedProposals === this.numberSeenProposals;
        },
        textClass: function () {
            return this.hasSeenAllSelectedProposals ? 'text-success' : '';
        },
        buttonClass: function () {
            return this.hasSeenAllSelectedProposals ? 'btn-success' : 'btn-default';
        },
    },
});
