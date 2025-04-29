<refine-candidates-tab
    :selected-candidates="selectedCandidates"
    :label-trees="labelTrees"
    :loading="loading"
    v-on:select="handleSelectedLabel"
    v-on:convert="handleConvertCandidates"
    ></refine-candidates-tab>
