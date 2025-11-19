<select-candidates-tab
    :label-trees="labelTrees"
    :candidates-count="candidates.length"
    :selected-candidates-count="selectedCandidates.length"
    @if ($acLimit !== INF ) :candidates-limit="{{$acLimit}}" @endif
    v-on:select="handleSelectedLabel"
    v-on:proceed="openRefineCandidatesTab"
    ></select-candidates-tab>
