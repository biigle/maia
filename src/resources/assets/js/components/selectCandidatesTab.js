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
    data: function () {
        return {
            //
        };
    },
    computed: {
        //
    },
    methods: {
        handleSelectedLabel: function (label) {
            this.$emit('select', label);
        },
        handleDeselectedLabel: function (label) {
            this.$emit('select', null);
        },
        proceed: function () {
            this.$emit('proceed');
        },
    },
    created: function () {
        //
    },
});
