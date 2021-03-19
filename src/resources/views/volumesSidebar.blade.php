@if (($user->can('edit-in', $volume) || $user->can('sudo')) && $volume->isImageVolume() && !$volume->hasTiledImages())
    <sidebar-tab name="maia" icon="robot" title="Perform Machine Learning Assisted Image Annotation (MAIA)" href="{{route('volumes-maia', $volume->id)}}"></sidebar-tab>
@endif
