<select-proposals-tab
    :proposals="proposals"
    :selected-proposals="selectedProposals"
    :locked="{{$job->state_id === $states['training-proposals']} ? 'false' : 'true'}}"
    @if ($tpLimit !== INF ) :proposal-limit="{{$tpLimit}}" @endif
    v-on:proceed="openRefineProposalsTab"
    ></select-proposals-tab>
