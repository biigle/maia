<refine-proposals-tab
    :selected-proposals="selectedProposals"
    :seen-proposals="selectedAndSeenProposals"
    :locked="{{$job->state_id === $states['training-proposals'] ? 'false' : 'true'}}"
    v-on:save="handleSaveProposals"
    ></refine-proposals-tab>
