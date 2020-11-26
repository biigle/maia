<refine-candidates-tab :selected-candidates="selectedCandidates" :label-trees="labelTrees" :loading="loading" v-on:select="handleSelectedLabel" v-on:convert="handleConvertCandidates" inline-template>
<div class="sidebar-tab__content sidebar-tab__content--maia">
    <div class="maia-tab-content__top">
        <label-trees
            :trees="labelTrees"
            :show-favourites="true"
            listener-set="select-candidates"
            v-on:select="handleSelectedLabel"
            v-on:deselect="handleDeselectedLabel"
            v-on:clear="handleDeselectedLabel"
            ></label-trees>
    </div>
    <div class="maia-tab-content__bottom">
        <div v-if="hasNoSelectedCandidates" class="panel panel-warning">
            <div class="panel-body text-warning">
                Please attach labels <i class="fas fa-check-square"></i> to annotation candidates.
            </div>
        </div>
        <div v-else v-cloak class="panel panel-info">
            <div class="panel-body text-info">
                Modify each annotation candidate with attached label, so that it fully encloses the interesting object or region of the image. Then convert the annotation candidates to real annotations.
            </div>
        </div>
        <button class="btn btn-success btn-block" :disabled="hasNoSelectedCandidates || loading" v-on:click="handleConvertCandidates">Convert annotation candidates</button>
    </div>
</div>
</refine-candidates-tab>
