@extends('manual.base')

@section('manual-title', 'About MAIA')

@section('manual-content')
    <div class="row">
        <p class="lead">
            An introduction to the Machine Learning Assisted Image Annotation method (MAIA).
        </p>
        <p>
            The goal of the MAIA method is to enable you to annotate large image collections much faster than with a purely manual "traditional" approach. To speed up image annotation, MAIA uses machine learning methods that automatically process the images. You can find a detailed description of MAIA in the paper <a href="#ref1">[1]</a>. Here is a short overview of the four consecutive stages of MAIA.
        </p>

        <h3>The four stages of MAIA</h3>

        <p>
            In the first stage of MAIA (novelty detection), the method attempts to automatically find "interesting" objects in the images. It does so by assuming that interesting objects are rare and distinguishable from a rather uniform background. This works well on many deep-sea images but is not perfect for every case. The detected interesting objects are passed to the second stage as "training proposals".
        </p>

        <p>
            As the training proposals usually contain many instances that actually are not interesting objects, they are manually filtered (by you) in the second stage. In this stage, you select only the actually interesting objects of all proposals. These are used in the third step.
        </p>

        <p>
            In addition to the novelty detection of the original MAIA method, BIIGLE offers alternative ways to obtain training proposals. Read more in the articles to use <a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">existing annotations</a> or <a href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}">knowledge transfer</a>.
        </p>

        <p>
            In the third step (instance segmentation), the manually filtered or automatically obtained set of training proposals is used to train a machine learning model for the automatic detection of the selected interesting objects. The model is highly specialized for this task and can usually detect most (if not all) instances of the interesting objects in the images. In the tests reported by the MAIA paper, 84% of the interesting objects were detected on average <a href="#ref1">[1]</a>. The detections are passed on as "annotation candidates" to the fourth step.
        </p>

        <p>
            As with the training proposals of the novelty detection, the annotation candidates can contain detections that are not actually interesting objects. In addition, the machine learning model only detects the objects and does not attempt to automatically assign labels to them. In the fourth step, the annotation candidates are again manually filtered to select only the actually interesting objects. Furthermore, labels are manually attached to the selected candidates which are subsequently transformed to actual annotations.
        </p>

        <p>
            While it is likely that some interesting objects are missed by MAIA, the process is much faster for large image collections than the purely manual approach. If you still need to make sure that all objects are annotated, you can use MAIA as a first step to annotate the majority of the objects and then add a manual sweep through the images to find objects that were missed. The objects that were detected using MAIA can serve as examples to identify the missed ones.
        </p>

        <h3>MAIA in BIIGLE</h3>

        <p>
            To create new annotations with MAIA in BIIGLE, project editors, experts or admins can start a new MAIA "job" for a volume of a project. To start a new MAIA job, click on the <button class="btn btn-default btn-xs"><i class="fas fa-robot"></i></button> button in the sidebar of the volume overview. This will open up the MAIA overview for the volume, which lists any running or finished jobs, as well as a form to create a new MAIA job for the volume. New jobs can only be created when no other job is currently running for the volume.
        </p>
        <p>
            The form to create a new MAIA job presents you a choice between several methods to obtain training data (training proposals). Choose one that best fits to your use case. The form initially shows only the parameters that are most likely to be modified for each job. To show all available parameters, click on the <button class="btn btn-default btn-xs">Show advanced parameters</button> button below the form. There can be quite a lot parameters that can be configured for a MAIA job. Although sensible defaults are set, a careful configuration may be crucial for a good quality of the resulting annotations. You can read more on the configuration parameters for <a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">novelty detection</a>, <a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">existing annotations</a>, <a href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}">knowledge transfer</a> and <a href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}">instance segmentation</a> in the respective articles.
        </p>
        <div class="panel panel-warning">
            <div class="panel-body text-warning">
                A MAIA job can run for many hours or even a day. Please choose your settings carefully before you submit a new job.
            </div>
        </div>
        <p>
            If novelty detection is chosen as the method to obtain training data, a MAIA job runs through the four consecutive stages outlined above. The first and the third stages perform automatic processing of the images. The second and the fourth stages require manual interaction by you. Once you have created a new job, it will immediately start the automatic processing of the first stage. BIIGLE will notify you when this stage is finished and the job is waiting for your manual interaction in the second stage. In the same way you are notified when the automatic processing of the third stage is finished. If existing annotations or knowledge transfer were chosen as the method to obtain training data, the job will directly proceed with the third stage, skipping the first two. You can change the way you receive new MAIA notifications in the <a href="{{route('settings-notifications')}}">notification settings</a> of your user account.
        </p>
        <p>
            The overview page of a MAIA job shows a main content area and a sidebar with multiple tabs. The first tab <button class="btn btn-default btn-xs"><i class="fas fa-info-circle"></i></button> shows general information about the job, including all the parameters that were used. The second <button class="btn btn-default btn-xs"><i class="fas fa-plus-square"></i></button> and third <button class="btn btn-default btn-xs"><i class="fas fa-pen-square"></i></button> tabs belong to the training proposals stage and are enabled once the job progresses to this stage. These tabs are visible only if novelty detection was chosen as the method to obtain training data. The fourth <button class="btn btn-default btn-xs"><i class="fas fa-check-square"></i></button> and fifth <button class="btn btn-default btn-xs"><i class="fas fa-pen-square"></i></button> tabs belong to the annotation candidates stage and are enabled once the job progresses to this stage.
        </p>
        <p>
            Continue reading about MAIA in the articles about the methods to obtain training data. You can start with the first method: <a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">novelty detection</a>.
        </p>
        <h3>Further reading</h3>
        <ul>
            <li><a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">Using novelty detection to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">Using existing annotations to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}">Using knowledge transfer to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">Reviewing the training proposals from novelty detection.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}">The automatic instance segmentation.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">Reviewing the annotation candidates from instance segmentation.</a></li>
        </ul>
    </div>
    <div class="row">
        <h3>References</h3>
        <ol>
            <li><a name="ref1"></a>Zurowietz, M., Langenkämper, D., Hosking, B., Ruhl, H. A., & Nattkemper, T. W. (2018). MAIA—A machine learning assisted image annotation method for environmental monitoring and exploration. PloS one, 13(11), e0207498. doi: <a href="https://doi.org/10.1371/journal.pone.0207498">10.1371/journal.pone.0207498</a></li>
        </ol>
    </div>
@endsection
