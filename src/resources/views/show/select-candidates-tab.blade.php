<select-candidates-tab :label-trees="labelTrees" v-on:proceed="openRefineCandidatesTab" inline-template>
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
        <div class="panel panel-info">
            <div class="panel-body text-info">
                Assign a label to each annotation candidate that you want to convert to a real annotation. Then proceed to the refinement of the annotation candidates.
            </div>
        </div>
        <button class="btn btn-default btn-block" v-on:click="proceed">Proceed</button>
    </div>
</div>
</select-candidates-tab>
