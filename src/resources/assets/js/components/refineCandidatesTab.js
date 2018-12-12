/**
 * The refine annotation candidates tab
 *
 * @type {Object}
 */
biigle.$component('maia.components.refineCandidatesTab', {
    components: {
        labelTrees: biigle.$require('labelTrees.components.labelTrees'),
    },
    props: {
        selectedCandidates: {
            type: Array,
            required: true,
        },
        labelTrees: {
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
        hasNoSelectedCandidates: function () {
            return this.selectedCandidates.length === 0;
        },
    },
    methods: {
        handleSelectedLabel: function (label) {
            this.$emit('select', label);
        },
        handleDeselectedLabel: function (label) {
            this.$emit('select', null);
        },
        handleConvertCandidates: function (label) {
            if (!this.hasNoSelectedCandidates) {
                this.$emit('convert');
            }
        },
    },
    created: function () {
        //
    },
});
