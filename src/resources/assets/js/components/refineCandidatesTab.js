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
    data() {
        return {
            //
        };
    },
    computed: {
        hasNoSelectedCandidates() {
            return this.selectedCandidates.length === 0;
        },
    },
    methods: {
        handleSelectedLabel(label) {
            this.$emit('select', label);
        },
        handleDeselectedLabel(label) {
            this.$emit('select', null);
        },
        handleConvertCandidates(label) {
            if (!this.hasNoSelectedCandidates) {
                this.$emit('convert');
            }
        },
    },
    created() {
        //
    },
});
