/**
 * The select annotation candidates tab
 *
 * @type {Object}
 */
biigle.$component('maia.components.selectCandidatesTab', {
    components: {
        labelTrees: biigle.$require('labelTrees.components.labelTrees'),
    },
    props: {
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
        //
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
    created() {
        //
    },
});
