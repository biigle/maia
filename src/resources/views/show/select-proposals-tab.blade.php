<select-proposals-tab
    :proposals-count="proposals.length"
    :selected-proposals-count="selectedProposals.length"
    :locked="{{$job->state_id === $states['training-proposals'] ? 'false' : 'true'}}"
    @if ($tpLimit !== INF ) :proposal-limit="{{$tpLimit}}" @endif
    v-on:proceed="openRefineProposalsTab"
    ></select-proposals-tab>
