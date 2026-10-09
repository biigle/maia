<select-proposals-tab
    :proposals-count="proposals.length"
    :selected-proposals-count="selectedProposals.length"
    :locked="{{$job->state === \Biigle\Modules\Maia\MaiaJobState::TRAINING_PROPOSALS ? 'false' : 'true'}}"
    @if ($tpLimit !== INF ) :proposal-limit="{{$tpLimit}}" @endif
    v-on:proceed="openRefineProposalsTab"
    ></select-proposals-tab>
