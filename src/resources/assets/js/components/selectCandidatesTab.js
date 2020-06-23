import {LabelTrees} from '../import';

/**
 * The select annotation candidates tab
 *
 * @type {Object}
 */
export default {
    components: {
        labelTrees: LabelTrees,
    },
    props: {
        labelTrees: {
            type: Array,
            required: true,
        },
    },
    methods: {
        handleSelectedLabel(label) {
            this.$emit('select', label);
        },
        handleDeselectedLabel(label) {
            this.$emit('select', null);
        },
        proceed() {
            this.$emit('proceed');
        },
    },
};
