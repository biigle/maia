@extends('manual.base')

@section('manual-title', 'Instance segmentation stage')

@section('manual-content')
    <div class="row">
        <p class="lead">
            The automatic instance segmentation.
        </p>
        <p>
            The third stage of a MAIA job processes all images of a volume with a supervised instance segmentation method (Mask&nbsp;R-CNN). This method uses the training proposals that were obtained with one of the three methods (<a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">novelty detection</a>, <a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">existing annotations</a> or <a href="{{route('manual-tutorials', ['maia', 'knowledge transfer'])}}">knowledge transfer</a>) to learn a model for what you determined to be interesting objects or regions in the images. The instance segmentation method produces a set of "annotation candidates", which are image regions that the method found to be interesting based on your provided training proposals. When the instance segmentation is finished, the MAIA job will continue to the <a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">next stage</a> in which you can manually review the annotation candidates.
        </p>

        <p>
            The instance segmentation stage of a MAIA job can take many hours to complete. In the MAIA paper <a href="#ref1">[1]</a> we found that a total of 30 training epochs takes about eleven hours to complete training. This duration can be influenced by the <a href="#configurable-parameters">configurable parameters</a> of this stage that you define when you initially submit the new MAIA job. Be careful not to choose too low numbers for the training epochs, else the quality of the annotation candidates might suffer greatly.
        </p>

        <h3><a name="configurable-parameters"></a>Configurable parameters</h3>

        <p>
            The configurable parameters for this stage are not shown by default in the form to submit a new MAIA job. Click on the <button class="btn btn-default btn-xs">Show advanced parameters</button> button below the form to show the parameters for the instance segmentation stage.
        </p>

        <h4><a name="training-scheme"></a>Training scheme</h4>

        <p class="text-muted">
            By default, the training scheme of UnKnoT <a href="#ref2">[2]</a> is used.
        </p>

        <p>
            A series of training steps consisting of layers to train, the number of epochs and the learning rate to use. Training should begin with the <code>heads</code> layers. The learning rate should decrease with subsequent steps.
        </p>

        <h3>Further reading</h3>
        <ul>
            <li><a href="{{route('manual-tutorials', ['maia', 'about'])}}">An introduction to the Machine Learning Assisted Image Annotation method (MAIA).</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">Using novelty detection to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">Using existing annotations to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}">Using knowledge transfer to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">Reviewing the training proposals from novelty detection.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">Reviewing the annotation candidates from instance segmentation.</a></li>
        </ul>
    </div>
    <div class="row">
        <h3>References</h3>
        <ol>
            <li><a name="ref1"></a>Zurowietz, M., Langenkämper, D., Hosking, B., Ruhl, H. A., & Nattkemper, T. W. (2018). MAIA—A machine learning assisted image annotation method for environmental monitoring and exploration. PloS one, 13(11), e0207498. doi: <a href="https://doi.org/10.1371/journal.pone.0207498">10.1371/journal.pone.0207498</a></li>
            <li><a name="ref2"></a>M. Zurowietz and T. W. Nattkemper, "Unsupervised Knowledge Transfer for Object Detection in Marine Environmental Monitoring and Exploration," in IEEE Access, vol. 8, pp. 143558-143568, 2020, doi: <a href="https://doi.org/10.1109/ACCESS.2020.3014441">10.1109/ACCESS.2020.3014441</a>.</li>
        </ol>
    </div>
@endsection
