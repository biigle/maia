<template>
    <div class="sidebar-tab__content sidebar-tab__content--maia">
        <div class="maia-tab-content__top">
            <p class="lead">
                {{selectedProposalsCount}} of {{proposalsCount}} selected
            </p>
            <div v-if="reachedLimit" class="panel panel-warning">
                <div class="panel-body text-warning">
                    This job reached the allowed maximum of {{proposalLimit}} training proposals!
                </div>
            </div>
            <div v-if="locked">
                <div class="panel panel-default">
                    <div class="panel-body">
                        The training proposals have been submitted and can no longer be edited.
                    </div>
                </div>
                <p class="text-muted">
                    Only selected training proposals are shown.
                </p>
            </div>
            <p v-else>
                The quality of annotation candidates directly depends on the number of selected training proposals. In some cases a few hundred may be sufficient, in other cases many more might be required.
            </p>
        </div>
        <div class="maia-tab-content__bottom">
            <div v-if="!locked">
                <div class="panel panel-info">
                    <div class="panel-body text-info">
                        Select the training proposals that show (part of) an interesting object or region of the image. Then proceed to the refinement of the training proposals.
                    </div>
                </div>
                <button class="btn btn-default btn-block" @click="proceed">Proceed</button>
            </div>
        </div>
    </div>
</template>

<script>
/**
 * The select training proposals tab
 *
 * @type {Object}
 */
export default {
    emits: ['proceed'],
    props: {
        proposalsCount: {
            type: Number,
            required: true,
        },
        selectedProposalsCount: {
            type: Number,
            required: true,
        },
        proposalLimit: {
            type: Number,
            default: Infinity,
        },
        locked: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        reachedLimit() {
            return this.proposalsCount >= this.proposalLimit;
        },
    },
    methods: {
        proceed() {
            this.$emit('proceed');
        },
    },
};
</script>
