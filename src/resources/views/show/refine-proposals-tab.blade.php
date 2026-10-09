@use('Biigle\Modules\Maia\MaiaJobState')
<refine-proposals-tab
    :selected-proposals="selectedProposals"
    :seen-proposals="selectedAndSeenProposals"
    :locked="{{$job->state === MaiaJobState::TRAINING_PROPOSALS ? 'false' : 'true'}}"
    v-on:save="handleSaveProposals"
    ></refine-proposals-tab>
