@extends('manual.base')

@section('manual-title', 'Novelty detection stage')

@section('manual-content')
    <div class="row">
        <p class="lead">
            A description of the first MAIA stage and the configurable parameters.
        </p>
        <p>
            The first stage of a MAIA job processes all images of a volume with an unsupervised novelty detection method. The novelty detection method attempts to find "interesting" objects or regions in the images, which are called "training proposals". The novelty detection acts without any prior knowledge of what is actually defined as interesting by you or anyone who wants to explore the images. Hence, the quality or meaningfulness of the training proposals may vary dramatically, depending on the images themselves and on what you are looking for.
        </p>
        <p>
            To make the novelty detection as flexible as possible, there are many parameters that can be configured before a new MAIA job is submitted. You might have to try out a few parameter combinations before the novelty detection produces meaningful training proposals. In cases where the novelty detection produces too few meaningful training proposals or does not work at all, you can augment the training proposals with <a href="#use-existing-annotations">your own annotations</a> or <a href="#skip-novelty-detection">skip the novelty detection</a> altogether.
        </p>

        <p>
            Once the novelty detection is finished for a MAIA job, it will continue to the <a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">training proposals stage</a>.
        </p>

        <h3><a name="configurable-parameters"></a>Configurable parameters</h3>

        <p>
            By default only the two parameters for the novelty detection are shown, that are the most likely to be modified for each new job. To show all configurable parameters, click on the <button class="btn btn-default btn-xs">Show advanced parameters</button> button below the form.
        </p>

        <h4>Number of image clusters</h4>

        <p class="text-muted">
            Integer between <code>1</code> and <code>100</code>. Default: <code>5</code>
        </p>

        <p>
            This parameter specifies the number of different kinds of images tha you expect. Images are of the same kind if they have similar lighting conditions or show similar patterns (e.g. sea floor, habitat types). Increase this number if you expect many different kinds of images. Lower the number to 1 if you have very few images and/or the content is largely uniform.
        </p>

        <p>
            If your novelty detection results in many large training proposals for very dark, bright or otherwise unusual images, consider to increase the number of image clusters and run the novelty detection again in a new MAIA job.
        </p>

        <p>
            The number of image clusters is denoted as <em>K</em> in the MAIA paper <a href="#ref1">[1]</a>.
        </p>

        <h4>Patch size</h4>

        <p class="text-muted">
            Integer between <code>3</code> and <code>99</code>. Default <code>39</code>
        </p>

        <p>
            This parameter specifies the size in pixels of the image patches used determine the training proposals. Increase the size if the images contain larger objects of interest, decrease the size if the objects are smaller. Larger patch sizes take longer to compute. Must be an odd number.
        </p>

        <p>
            The size of objects or regions of interest is relative to the image and directly depends on the distance between the objects and the camera (e.g. the sea floor and the camera). If the camera is close to the objects, choose a larger patch size, if the camera is farther away, choose a smaller patch size.
        </p>

        <p>
            The patch size is denoted as <em>r<sub>e</sub></em> in the MAIA paper <a href="#ref1">[1]</a>.
        </p>

        <h4>Threshold percentile</h4>

        <p class="text-muted">
            Integer between <code>0</code> and <code>99</code>. Default <code>99</code>
        </p>

        <p>
            This is the percentile of pixel saliency values used to determine the saliency threshold. Lower this value to get more training proposals. The default value should be fine for most cases.
        </p>

        <p>
            The threshold percentile is denoted as P<sub>99</sub> in the MAIA paper <a href="#ref1">[1]</a>. The 99 is the parameter that you can configure here.
        </p>

        <h4>Latent layer size</h4>

        <p class="text-muted">
            Number between <code>0.05</code> and <code>0.75</code>. Default <code>0.1</code>
        </p>

        <p>
            The learning capability used to determine training proposals. Increase this number to ignore more complex "uninteresting" objects and patterns.
        </p>

        <p>
            The latent layer size is denoted as the compression factor in <em>s</em>&nbsp;=&nbsp;[0.1<em>r</em>] in the MAIA paper <a href="#ref1">[1]</a>. The 0.1 is the parameter that you can configure here.
        </p>

        <h4>Number of training patches</h4>

        <p class="text-muted">
            Integer between <code>1000</code> and <code>100000</code>. Default <code>10000</code>
        </p>

        <p>
            The number of training image patches used to determine training proposals. You can increase this number for a large volume but it will take longer to compute.
        </p>

        <p>
            The number of training patches is the number of samples that is used to train an autoencoder network for one cluster of images <em>U<sub>k</sub></em> as described in the MAIA paper <a href="#ref1">[1]</a>.
        </p>

        <h4>Number of training epochs</h4>

        <p class="text-muted">
            Integer between <code>50</code> and <code>1000</code>. Default <code>100</code>
        </p>

        <p>
            This parameter specifies the time spent on training of an autoencoder network when determining the training proposals. The more time is spent on training, the more complex "uninteresting" objects or patterns can be ignored.
        </p>

        <p>
            The number of training epochs is the number of epochs that each autoencoder network is trained for one cluster of images <em>U<sub>k</sub></em> as described in the MAIA paper <a href="#ref1">[1]</a>.
        </p>

        <h4>Stride</h4>

        <p class="text-muted">
            Integer between <code>1</code> and <code>10</code>. Default <code>2</code>
        </p>

        <p>
            A higher stride increases the speed of the novelty detection but reduces the sensitivity to small regions or objects. Set the stride to 1 to disable the speed optimization and process the images in their original resolution. In the MAIA paper <a href="#ref1">[1]</a>, we found that a stride of 2 does not reduce the performance of the novelty detection. You might be able to use an even higher stride than that.
        </p>

        <p>
            The stride is used for the "convolution operation" in which each DBM<sub><em>k</em></sub> is applied to the images of a cluster <em>U<sub>k</sub></em> as described in the MAIA paper <a href="#ref1">[1]</a>.
        </p>

        <h4>Ignore radius</h4>

        <p class="text-muted">
            Integer greater than or equal to <code>0</code>. Default <code>5</code>
        </p>

        <p>
            Ignore training proposals or annotation candidates which have a radius smaller or equal than this parameter in pixels. You can use this to filter out training proposals that have a smaller size than the objects or regions of interest that you expect. Fewer training proposals mean a lower workload for you in the training proposals stage of MAIA. The default value of 5 pixels is sensible because it is unlikely that smaller objects can be accurately identified.
        </p>

        <p>
            In the MAIA paper <a href="#ref1">[1]</a> no training proposals were ignored which is equivalent to an ignore radius of 0.
        </p>

        <h3><a name="use-existing-annotations"></a>Use existing annotations</h3>

        <p>
            If you already have existing annotations for the volume or the novelty detection does not produce (enough) meaningful training proposals, you can select the checkbox "Use existing annotations" before you submit a new MAIA job. When this checkbox is checked, the existing annotations are also presented as training proposals in the second MAIA stage.
        </p>

        <p>
            If the checkbox is selected, a new input appears that allows you to limit the used existing annotations to one or more labels. This way you can select only those annotations that make sense as training proposals. If you do not choose any label, all existing annotations are used as training proposals.
        </p>

        <h3><a name="skip-novelty-detection"></a>Skip novelty detection</h3>

        <p>
            If you chose to use existing annotations as training proposals, a new checkbox to "Skip novelty detection" appears. Select this checkbox if you do not want to run the novelty detection at all and only want to use the existing annotations as training proposals.
        </p>

        <p>
            Please note that the new MAIA job is shown as "running novelty detection" in the job overview page even if you chose to skip novelty detection. Just refresh the job overview page after a few seconds and the job should have proceeded to the second MAIA stage.
        </p>

    </div>
    <div class="row">
        <h3>References</h3>
        <ol>
            <li><a name="ref1"></a>Zurowietz, M., Langenkämper, D., Hosking, B., Ruhl, H. A., & Nattkemper, T. W. (2018). MAIA—A machine learning assisted image annotation method for environmental monitoring and exploration. PloS one, 13(11), e0207498. doi: <a href="https://doi.org/10.1371/journal.pone.0207498">10.1371/journal.pone.0207498</a></li>
        </ol>
    </div>
@endsection
