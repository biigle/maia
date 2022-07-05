@extends('manual.base')

@section('manual-title', 'Existing annotations')

@section('manual-content')
    <div class="row">
        <p class="lead">
            Using existing annotations to obtain training data.
        </p>
        <p>
            This method allows you to choose existing annotations in the same volume as training data for the instance segmentation stage. All annotations will be converted to circles and the new MAIA job will immediately proceed to the <a href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}">instance segmentation stage</a>.
        </p>

        <h3><a name="configurable-parameters"></a>Configurable parameters</h3>

        <p>
            To show the configurable parameters, click on the <button class="btn btn-default btn-xs">Show advanced parameters</button> button below the form.
        </p>

        <h4>Restrict to labels</h4>

        <p class="text-muted">
            By default, all annotations are used.
        </p>

        <p>
            Use the input field to select one or more labels. If present, only annotations with the chosen labels are used as training data for the MAIA job. If no labels are chosen, all annotations are used.
        </p>

        <h4>Show training proposals</h4>

        <p class="text-muted">
            Off by default.
        </p>

        <p>
            Show the existing annotations as selectable training proposals before continuing to the instance segmentation stage. This allows you to select only a subset of the existing annotations as training data (regardless their label).
        </p>

        <h4>Ignore Existing Labels</h4>

        <p class="text-muted">
            Off by default.
        </p>

        <p>
            Ignores the Existing Labels to be attached to the corresponding training proposals. By default, if "Show training proposals" box is checked then the Existing Labels are attached to the training proposal and is visible in "Select Training Proposal" section.
        </p>

        <h3>Further reading</h3>
        <ul>
            <li><a href="{{route('manual-tutorials', ['maia', 'about'])}}">An introduction to the Machine Learning Assisted Image Annotation method (MAIA).</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">Using novelty detection to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}">Using knowledge transfer to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">Reviewing the training proposals from novelty detection.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}">The automatic instance segmentation.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">Reviewing the annotation candidates from instance segmentation.</a></li>
        </ul>
    </div>
@endsection
