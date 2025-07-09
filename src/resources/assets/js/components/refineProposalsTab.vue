<template>
    <div class="sidebar-tab__content sidebar-tab__content--maia">
        <div class="maia-tab-content__top">
            <div v-if="locked" class="panel panel-default">
                <div class="panel-body">
                    The training proposals have been submitted and can no longer be edited.
                </div>
            </div>
            <div v-else>
                <p class="lead" :class="textClass">
                    {{numberSeenProposals}} of {{numberSelectedProposals}} seen
                </p>
                <p>
                    The quality of annotation candidates directly depends on the number of selected training proposals. In some cases a few hundred may be sufficient, in other cases many more might be required.
                </p>
            </div>
        </div>
        <div class="maia-tab-content__bottom">
            <div v-if="!locked">
                <div v-if="hasNoSelectedProposals" class="panel panel-warning">
                    <div class="panel-body text-warning">
                        Please select <i class="fas fa-plus-square"></i> training proposals.
                    </div>
                </div>
                <div v-else class="panel panel-info">
                    <div class="panel-body text-info">
                        Modify each selected training proposal, so that it fully encloses the interesting object or region of the image. Then submit the training proposals to continue with MAIA.
                    </div>
                </div>

                <form @submit.prevent="handleSubmit">
                    <button
                        type="submit"
                        class="btn btn-block"
                        :class="buttonClass"
                        :disabled="hasNoSelectedProposals || null"
                        >
                        Submit training proposals
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ['save'],
    props: {
        selectedProposals: {
            type: Array,
            required: true,
        },
        seenProposals: {
            type: Array,
            required: true,
        },
        locked: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        numberSelectedProposals() {
            return this.selectedProposals.length;
        },
        numberSeenProposals() {
            return this.seenProposals.length;
        },
        hasNoSelectedProposals() {
            return this.numberSelectedProposals === 0;
        },
        hasSeenAllSelectedProposals() {
            return this.numberSelectedProposals > 0 && this.numberSelectedProposals === this.numberSeenProposals;
        },
        textClass() {
            return this.hasSeenAllSelectedProposals ? 'text-success' : '';
        },
        buttonClass() {
            return this.hasSeenAllSelectedProposals ? 'btn-success' : 'btn-default';
        },
    },
    methods: {
        handleSubmit() {
            const doContinue = confirm('Once the training proposals have been submitted, you are no longer able to modify them. Continue?');
            if (doContinue) {
                this.$emit('save');
            }
        },
    },
};
</script>
