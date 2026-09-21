<refine-candidates-tab
    :selected-candidates="selectedCandidates"
    :label-trees="labelTrees"
    :sorting-project-ids="projectIds"
    :loading="loading"
    v-on:select="handleSelectedLabel"
    v-on:convert="handleConvertCandidates"
    ></refine-candidates-tab>
