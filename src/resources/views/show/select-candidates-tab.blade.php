<select-candidates-tab
    :label-trees="labelTrees"
    :sorting-project-ids="projectIds"
    :selected-label="selectedLabel"
    :annotation-guideline="annotationGuideline"
    :annotation-guidelines="annotationGuidelines"
    :must-use-annotation-guideline="mustUseAnnotationGuideline"
    :volume-id="job.volume_id"
    :candidates-count="candidates.length"
    :selected-candidates-count="selectedCandidates.length"
    @if ($acLimit !== INF ) :candidates-limit="{{$acLimit}}" @endif
    v-on:select="handleSelectedLabel"
    v-on:select-guideline="handleSelectedAnnotationGuideline"
    v-on:proceed="openRefineCandidatesTab"
    ></select-candidates-tab>
