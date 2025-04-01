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
            <div v-if="hasNoSelectedCandidates" class="panel panel-warning">
                <div class="panel-body text-warning">
                    Please attach labels <i class="fas fa-check-square"></i> to annotation candidates.
                </div>
            </div>
            <div v-else class="panel panel-info">
                <div class="panel-body text-info">
                    Modify each annotation candidate with attached label, so that it fully encloses the interesting object or region of the image. Then convert the annotation candidates to real annotations.
                </div>
            </div>
            <button
                class="btn btn-success btn-block"
                :disabled="cannotConvert || null"
                @click="handleConvertCandidates"
                >
                Convert annotation candidates
            </button>
        </div>
    </div>
</template>
<script>
import {LabelTrees} from '../import.js';

/**
 * The refine annotation candidates tab
 *
 * @type {Object}
 */
export default {
    emits: [
        'select',
        'convert',
    ],
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
        cannotConvert() {
            return this.hasNoSelectedCandidates || this.loading;
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
