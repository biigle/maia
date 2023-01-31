@extends('manual.base')

@section('manual-title', 'Knowledge transfer')

@section('manual-content')
    <div class="row">
        <p class="lead">
            Using knowledge transfer to obtain training data.
        </p>

        <p>
            This method allows you to choose existing annotations of another volume as training data for the object detection stage. This is done using the "knowledge transfer" method UnKnoT <a href="#ref1">[1]</a>. All annotations will be converted to circles and the new MAIA job will immediately proceed to the <a href="{{route('manual-tutorials', ['maia', 'object-detection'])}}">object detection stage</a>. This method can only be used if <a href="{{route('manual-tutorials', ['volumes', 'image-metadata'])}}">distance to ground</a> or <a href="{{route('manual-tutorials', ['volumes', 'image-metadata'])}}">image area</a> information is available for all images of the volume of the MAIA job.
        </p>

        <div class="panel panel-info">
            <div class="panel-body text-info">
                UnKnoT using the image area is an extension of the original method that offers an alternative to using the distance to ground information.
            </div>
        </div>

        <h3><a name="configurable-parameters"></a>Configurable parameters</h3>

        <p>
            To show all configurable parameters, click on the <button class="btn btn-default btn-xs">Show advanced parameters</button> button below the form.
        </p>

        <h4>Volume</h4>

        <p>
            The volume of which to use annotations as training data for the object detection stage. Only volumes with <a href="{{route('manual-tutorials', ['volumes', 'image-metadata'])}}">distance to ground</a> or <a href="{{route('manual-tutorials', ['volumes', 'image-metadata'])}}">image area</a> information for all images can be selected. The annotations should show the same or very similar object classes than those that should be found with the MAIA job.
        </p>

        <h4>Restrict to labels</h4>

        <p class="text-muted">
            By default, all annotations are used.
        </p>

        <p>
            Use the input field to select one or more labels. If present, only annotations with the chosen labels are used as training data for the MAIA job. If no labels are chosen, all annotations are used.
        </p>

        <h3>Further reading</h3>
        <ul>
            <li><a href="{{route('manual-tutorials', ['maia', 'about'])}}">An introduction to the Machine Learning Assisted Image Annotation method (MAIA).</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">Using novelty detection to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">Using existing annotations to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">Reviewing the training proposals from novelty detection.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'object-detection'])}}">The automatic object detection.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">Reviewing the annotation candidates from object detection.</a></li>
        </ul>
    </div>

     <div class="row">
        <h3>References</h3>
        <ol>
            <li><a name="ref1"></a>M. Zurowietz and T. W. Nattkemper, "Unsupervised Knowledge Transfer for Object Detection in Marine Environmental Monitoring and Exploration," in IEEE Access, vol. 8, pp. 143558-143568, 2020, doi: <a href="https://doi.org/10.1109/ACCESS.2020.3014441">10.1109/ACCESS.2020.3014441</a></li>
        </ol>
    </div>
@endsection
