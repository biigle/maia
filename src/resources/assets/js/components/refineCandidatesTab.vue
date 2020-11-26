<script>
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
        loading: {
            type: Boolean,
            default: false,
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
        handleDeselectedLabel() {
            this.$emit('select', null);
        },
        handleConvertCandidates() {
            if (!this.hasNoSelectedCandidates) {
                this.$emit('convert');
            }
        },
    },
};
</script>
