@extends('manual.base')

@section('manual-title', 'Annotation candidates stage')

@section('manual-content')
    <div class="row">
        <p class="lead">
            A description of the last MAIA stage.
        </p>

        <p>
            A MAIA job is finished when it proceeded from the <a href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}">instance segmentation</a> to the annotation candidates stage. In this stage you can review the annotation candidates that were generated in the previous stage and convert them to real annotations. Similar to the <a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">training proposals</a> stage, this is done in two steps the <a href="#selection-of-annotation-candidates">selection of annotation candidates</a> and the <a href="#refinement-of-annotation-candidates">refinement of annotation candidates</a>.
        </p>

        <h3><a name="selection-of-annotation-candidates"></a>Selection of annotation candidates</h3>

        <p>
            The selection of annotation candidates is very similar to the <a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}#selection-of-training-proposals">selection of training proposals</a>. When you open the <button class="btn btn-default btn-xs"><i class="fas fa-check-square"></i></button>&nbsp;select annotation candidates tab in the sidebar, the annotation candidates are loaded and their image thumbnails are displayed in a regular grid. Please refer to the respective <a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}#selection-of-training-proposals">manual article</a> to learn how to interact with the thumbnail grid.
        </p>

        <p>
            The single difference between the selection of training proposals and the selection of annotation candidates is that you have to attach labels to annotation candidates when you select them. Before you can select an annotation candidate, choose the label from the label trees in the sidebar that describes what you can see in the image thumbnail of the annotation candidate. With the label selected, click on the thumbnail of the annotation candidate to select and attach the label to it.
        </p>

        <p class="text-center">
            <img src="{{asset('vendor/maia/images/select_ac_1.jpg')}}" width="30%">
            <img src="{{asset('vendor/maia/images/select_ac_2.jpg')}}" width="30%">
            <img src="{{asset('vendor/maia/images/select_ac_3.jpg')}}" width="30%">
        </p>

        <p>
            We recommend you to go multiple times through the process of selecting annotation candidates, each time with a different label. It is much faster to review all image thumbnails of the annotation candidates and look only for a single class of objects or image regions than to switch frequently between many different classes.
        </p>

        <p>
            When you are finished with the selection of annotation candidates, continue to the refinement step.
        </p>

        <h3><a name="refinement-of-annotation-candidates"></a>Refinement of annotation candidates</h3>

        <p>
            The refinement step for annotation candidates is also very similar to the <a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}#refinement-of-training-proposals">refinement step for training proposals</a>. You cycle through all selected annotation candidates and modify the circle of each annotation candidate to fit to the object or region that it should mark. Please refer to the respective <a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}#refinement-of-training-proposals">manual article</a> to learn how to interact with the refinement tool.
        </p>

        <p>
            You can select annotation candidates in the refinement tool as well. First select a label from the sidebar, then click on the <button class="btn btn-default btn-xs"><i class="fas fa-check"></i></button> button at the bottom and then click on the semitransparent dashed circle of the annotation candidate that you want to select and to which you want to attach the label.
        </p>

        <h3><a name="conversion-of-annotation-candidates"></a>Conversion of annotation candidates</h3>

        <p>
            When you are statisfied with the labels and circles of the selected annotation candidates, click on the <button class="btn btn-success btn-xs">Convert annotation candidates</button> button in the <button class="btn btn-default btn-xs"><i class="fas fa-pen-square"></i></button>&nbsp;refine annotation candidates tab. This will convert all selected annotation candidates to real annotations.
        </p>

        <p class="text-center">
            <img src="{{asset('vendor/maia/images/converted_ac_1.jpg')}}" width="30%">
            <img src="{{asset('vendor/maia/images/converted_ac_2.jpg')}}" width="30%">
        </p>

        <p>
            Converted annotation candidates can no longer be modified. They are displayed with a <i class="fas fa-lock"></i> icon in the thumbnail grid and as gray circles in the refinement tool. However, you can repeat the selection, refinement and conversion of annotation candidates as long as you like, until all annotation candidates of interest have been converted.
        </p>

        <p>
            The creation of new annotations concludes a MAIA job. You can either keep the MAIA job for reference or delete it with a click on <button class="btn btn-danger btn-xs">Delete this job</button> in the <button class="btn btn-default btn-xs"><i class="fas fa-info-circle"></i></button> job information tab in the sidebar. Please remember to cite the appropriate <a href="{{route('manual')}}#references">reference publications</a> if you use MAIA for one of your studies.
        </p>
    </div>
@endsection
