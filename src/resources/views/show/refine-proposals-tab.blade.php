<refine-proposals-tab
    :selected-proposals="selectedProposals"
    :seen-proposals="selectedAndSeenProposals"
    :locked="{{$job->state === \Biigle\Modules\Maia\MaiaJobState::TRAINING_PROPOSALS ? 'false' : 'true'}}"
    v-on:save="handleSaveProposals"
    ></refine-proposals-tab>
