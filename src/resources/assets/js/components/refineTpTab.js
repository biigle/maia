/**
 * The refine training proposals tab
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineTpTab', {
    props: {
        selectedTrainingProposals: {
            type: Array,
            required: true,
        },
        seenTrainingProposals: {
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
        numberSelectedTps: function () {
            return this.selectedTrainingProposals.length;
        },
        numberSeenTps: function () {
            return this.seenTrainingProposals.length;
        },
        hasNoSelectedTp: function () {
            return this.numberSelectedTps === 0;
        },
        hasSeenAllSelectedTps: function () {
            return this.numberSelectedTps > 0 && this.numberSelectedTps === this.numberSeenTps;
        },
        textClass: function () {
            return this.hasSeenAllSelectedTps ? 'text-success' : '';
        },
        buttonClass: function () {
            return this.hasSeenAllSelectedTps ? 'btn-success' : 'btn-default';
        },
    },
    methods: {
        //
    },
    created: function () {
        //
    },
});
