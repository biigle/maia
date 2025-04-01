<template>
    <div class="sidebar-tab__content sidebar-tab__content--maia">
        <div class="maia-tab-content__top">
            <label-trees
                :trees="labelTrees"
                :show-favourites="true"
                listener-set="select-candidates"
                @select="handleSelectedLabel"
                @deselect="handleDeselectedLabel"
                @clear="handleDeselectedLabel"
                ></label-trees>
        </div>
        <div class="maia-tab-content__bottom">
            <div class="panel panel-info">
                <div class="panel-body text-info">
                    Attach a label to each annotation candidate that you want to convert to a real annotation. Then proceed to the refinement of the annotation candidates.
                </div>
            </div>
            <button class="btn btn-default btn-block" @click="proceed">Proceed</button>
        </div>
    </div>
</template>
<script>
import {LabelTrees} from '../import.js';

/**
 * The select annotation candidates tab
 *
 * @type {Object}
 */
export default {
    emits: [
        'select',
        'proceed',
    ],
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
        handleDeselectedLabel() {
            this.$emit('select', null);
        },
        proceed() {
            this.$emit('proceed');
        },
    },
};
</script>
