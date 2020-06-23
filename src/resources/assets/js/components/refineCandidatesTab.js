import {LabelTrees} from '../import';

/**
 * The refine annotation candidates tab
 *
 * @type {Object}
 */
export default {
    components: {
        labelTrees: LabelTrees,
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
};
