@extends('manual.base')

@section('manual-title', 'About MAIA')

@section('manual-content')
    <div class="row">
        <p class="lead">
            An introduction to the Machine Learning Assisted Image Annotation method (MAIA).
        </p>
        <p>
            The goal of the MAIA method is to enable you to annotate large image collections much faster than with a purely manual "traditional" approach. To speed up image annotation, MAIA uses machine learning methods that automatically process the images. You can find a detailed description of MAIA in the paper <a href="#ref1">[1]</a>.
        </p>
        <p>
            To create new annotations with MAIA in BIIGLE, project editors, experts or admins can start a new MAIA "job" for a volume of a project. To start a new MAIA job, click on the <button class="btn btn-default btn-xs"><i class="fas fa-robot"></i></button> button in the sidebar of the volume overview. This will open up the MAIA overview for the volume, which lists any running or finished jobs, as well as a form to create a new MAIA job for the volume. New jobs can only be created when no other job is currently running for the volume.
        </p>
        <p>
            The form to create a new MAIA job initially shows only the parameters that are most likely to be modified for each job. To show all available parameters, click on the <button class="btn btn-default btn-xs">Show advanced parameters</button> button below the form. There are quite a lot parameters that can be configured for a MAIA job. Although sensible defaults are set, a careful configuration may be crucial for a good quality of the resulting annotations. You can read more on the configuration parameters of the <a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">novelty detection stage</a> and those of the <a href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}">instance segmentation stage</a> in the respective articles.
        </p>
        <div class="panel panel-warning">
            <div class="panel-body text-warning">
                A MAIA job can run for many hours or even a day. Please choose your parameters carefully before you submit a new job.
            </div>
        </div>
        <p>
            A MAIA job runs through four consecutive stages. The first and the third stages perform automatic processing of the images. The second and the fourth stages require manual interaction by you. Once you have created a new job, it will immediately start the automatic processing of the first stage. BIIGLE will notify you when this stage is finished and the job is waiting for your manual interaction in the second stage. In the same way you are notified when the automatic processing of the third stage is finished. You can change the way you receive new MAIA notifications in the <a href="{{route('settings-notifications')}}">notification settings</a> of your user account.
        </p>
        <p>
            The overview page of a MAIA job shows a main content area and a sidebar with five different tabs. The first tab <button class="btn btn-default btn-xs"><i class="fas fa-info-circle"></i></button> shows general information about the job, including all the parameters that were used. The second <button class="btn btn-default btn-xs"><i class="fas fa-plus-square"></i></button> and third <button class="btn btn-default btn-xs"><i class="fas fa-pen-square"></i></button> tabs belong to the training proposals stage and are enabled once the job progresses to this stage. The fourth <button class="btn btn-default btn-xs"><i class="fas fa-check-square"></i></button> and fifth <button class="btn btn-default btn-xs"><i class="fas fa-pen-square"></i></button> tabs belong to the annotation candidates stage and are enabled once the job progresses to this stage.
        </p>
        <p>
            Continue reading about MAIA in the articles about the individual stages. You can start with the first stage: <a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">novelty detection</a>.
        </p>
        <ul>
            <li><a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">A description of the first MAIA stage: Novelty detection.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">A description of the second MAIA stage: Training proposals.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}">A description of the third MAIA stage: Instance segmentation.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">A description of the last MAIA stage: Annotation candidates.</a></li>
        </ul>
    </div>
    <div class="row">
        <h3>References</h3>
        <ol>
            <li><a name="ref1"></a>Zurowietz, M., Langenkämper, D., Hosking, B., Ruhl, H. A., & Nattkemper, T. W. (2018). MAIA—A machine learning assisted image annotation method for environmental monitoring and exploration. PloS one, 13(11), e0207498. doi: <a href="https://doi.org/10.1371/journal.pone.0207498">10.1371/journal.pone.0207498</a></li>
        </ol>
    </div>
@endsection
