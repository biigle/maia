@extends('manual.base')

@section('manual-title', 'Instance segmentation stage')

@section('manual-content')
    <div class="row">
        <p class="lead">
            A description of the third MAIA stage and the configurable parameters.
        </p>
        <p>
            The third stage of a MAIA job processes all images of a volume with a supervised instance segmentation method (Mask&nbsp;R-CNN). This method uses the training proposals that you have selected and refined in the <a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">previous stage</a> of the MAIA job to learn a model for what you determined to be interesting objects or regions in the images. The instance segmentation method produces a set of "annotation candidates", which are image regions that the method found to be interesting based on your provided training proposals. When the instance segmentation is finished, the MAIA job will continue to the <a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">next stage</a> in which you can manually review the annotation candidates.
        </p>

        <p>
            The instance segmentation stage of a MAIA job can take many hours to complete. In the MAIA paper <a href="#ref1">[1]</a> we found that a total of 30 training epochs takes about eleven hours to complete training. This duration can be influenced by the <a href="#configurable-parameters">configurable parameters</a> of this stage that you define when you initially submit the new MAIA job. Be careful not to choose too low numbers for the training epochs, else the quality of the annotation candidates might suffer greatly.
        </p>

        <h3><a name="configurable-parameters"></a>Configurable parameters</h3>

        <p>
            Some configurable parameters for this stage are not shown by default in the form to submit a new MAIA job. Click on the <button class="btn btn-default btn-xs">Show advanced parameters</button> button below the form to show these parameters for the instance segmentation stage.
        </p>

        <h4><a name="number-of-training-epochs-head"></a>Number of training epochs (head)</h4>

        <p class="text-muted">
            Integer greater than or equal to <code>1</code>. Default <code>20</code>
        </p>

        <p>
            Time spent on training only the head layers of Mask R-CNN for instance segmentation. This is faster and should be a higher number than epochs (all).
        </p>

        <h4><a name="number-of-training-epochs-all"></a>Number of training epochs (all)</h4>

        <p class="text-muted">
            Integer greater than or equal to <code>1</code>. Default <code>10</code>
        </p>

        <p>
            Time spent on training all layers of Mask R-CNN for instance segmentation. This is slower and should be a lower number than epochs (head).
        </p>

        <h4><a name="store-trained-model"></a>Store trained model</h4>

        <p>
            When checked, the instance segmentation model will be permanently stored and can be reused for another MAIA job.
        </p>

        <h4><a name="description"></a>Description</h4>

        <p>
            Description for the job so the stored instance segmentation model can be found later.
        </p>
    </div>
    <div class="row">
        <h3>References</h3>
        <ol>
            <li><a name="ref1"></a>Zurowietz, M., Langenkämper, D., Hosking, B., Ruhl, H. A., & Nattkemper, T. W. (2018). MAIA—A machine learning assisted image annotation method for environmental monitoring and exploration. PloS one, 13(11), e0207498. doi: <a href="https://doi.org/10.1371/journal.pone.0207498">10.1371/journal.pone.0207498</a></li>
        </ol>
    </div>
@endsection
