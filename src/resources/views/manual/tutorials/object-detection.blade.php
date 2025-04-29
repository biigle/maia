@extends('manual.base')

@section('manual-title', 'Object detection stage')

@section('manual-content')
    <div class="row">
        <p class="lead">
            The automatic object detection.
        </p>
        <p>
            The third stage of a MAIA job processes all images of a volume with a supervised object detection method. This method uses the training proposals that were obtained with one of the three methods (<a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">novelty detection</a>, <a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">existing annotations</a> or <a href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}">knowledge transfer</a>) to learn a model for what you determined to be interesting objects or regions in the images. The object detection method produces a set of "annotation candidates", which are image regions that the method found to be interesting based on your provided training proposals. When the object detection is finished, the MAIA job will continue to the <a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">next stage</a> in which you can manually review the annotation candidates.
        </p>

        <p>
            The object detection stage of a MAIA job can take many hours to complete. The exact runtime depends on the size of the dataset and the capabilities of the computing hardware. But generally, more training data results in a better object detector, so don't hold back your annotated data to reduce the training time.
        </p>

        <p>
            In the original MAIA paper <a href="#ref1">[1]</a>, Mask&nbsp;R-CNN was used for instance segmentation in this stage. However, it was no "real" instance segmentation, as the segmentation masks were converted back to bounding boxes. In the implementation in BIIGLE, Faster&nbsp;R-CNN is used for object detection, which directly produces bounding boxes (but is otherwise similar to Mask&nbsp;R-CNN).
        </p>

        <h3>Further reading</h3>
        <ul>
            <li><a href="{{route('manual-tutorials', ['maia', 'about'])}}">An introduction to the Machine Learning Assisted Image Annotation method (MAIA).</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">Using novelty detection to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">Using existing annotations to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}">Using knowledge transfer to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">Reviewing the training proposals from novelty detection.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">Reviewing the annotation candidates from object detection.</a></li>
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
