<h3>Create a new MAIA job <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['maia', 'about'])}}" title="More information on MAIA" target="_blank"><i class="fas fa-info-circle"></i></a></h3>
<p>
    A job can run for many hours or even a day. Please choose your settings carefully before you submit a new job.
</p>
<form id="maia-job-form" method="POST" action="{{ url("api/v1/volumes/{$volume->id}/maia-jobs") }}" v-on:submit="submit">
    <fieldset>
        <legend>Training data method</legend>
        <div>
            The instance segmentation stage of MAIA requires training data with objects of interest. You can choose from several methods to obtain the training data:
        </div>
        <div class="form-group">
            <div class="radio">
                <label for="training_data_novelty_detection">
                    <input type="radio" id="training_data_novelty_detection" name="training_data_method" value="novelty_detection" v-model="trainingDataMethod">
                    <strong>Novelty detection</strong>
                    <div class="help-block">
                        Novelty detection attempts to find objects of interest in images where the background (e.g. sea floor) is rather uniform. It does not require prior annotations but has many parameters that can be tuned. <a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">More information.</a>
                    </div>
                </label>
            </div>
            <div class="radio">
                <label for="training_data_own_annotations" @if (!$canUseExistingAnnotations) class="text-muted" @endif>
                    <input type="radio" id="training_data_own_annotations" name="training_data_method" value="own_annotations" v-model="trainingDataMethod" @if (!$canUseExistingAnnotations) disabled @endif>
                    <strong>Existing annotations</strong>
                    @if (!$canUseExistingAnnotations)
                        <div class="text-warning">No existing annotations available for this volume.</div>
                    @endif
                    <div class="help-block">
                        This method uses existing annotations in this volume as training data. All annotations will be converted to circles. <a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">More information.</a>
                    </div>
                </label>
            </div>
            <div class="radio">
                <label for="training_data_unknot" @if (!$canUseKnowledgeTransfer) class="text-muted" @endif>
                    <input type="radio" id="training_data_unknot" name="training_data_method" value="knowledge_transfer" v-model="trainingDataMethod" @if (!$canUseKnowledgeTransfer) disabled @endif>
                    <strong>Knowledge transfer</strong>
                    @if (!$canUseKnowledgeTransfer)
                        <div class="text-warning">No distance to ground information available for this volume. <a href="{{route('manual-tutorials', ['volumes', 'image-metadata'])}}" title="Find out how to add distance to ground information"><i class="fa fa-question-circle"></i></a></div>
                    @endif
                    <div class="help-block">
                        Knowlegde transfer uses existing annotations of another volume as training data. The images should be very similar to the ones of this volume and the classes of objects of interest should be the same. This method requires that the distance to ground metadata is present for every image of the two volumes. <a href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}">More information.</a>
                    </div>
                </label>
            </div>
        </div>
    </fieldset>

    <fieldset v-if="useNoveltyDetection">
        <legend v-cloak v-show="showAdvanced">Novelty Detection <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}#configurable-parameters" title="More information on the configurable parameters for novelty detection" target="_blank"><i class="fas fa-info-circle"></i></a></legend>
        <div class="form-group{{ $errors->has('nd_clusters') ? ' has-error' : '' }}">
            <label for="nd_clusters">Number of image clusters</label>
            <input type="number" class="form-control" name="nd_clusters" id="nd_clusters" value="{{ old('nd_clusters', 5) }}" required min="1" max="100" step="1">
            @if($errors->has('nd_clusters'))
               <span class="help-block">{{ $errors->first('nd_clusters') }}</span>
            @else
                <span class="help-block">
                    Number of different kinds of images to expect. Images are of the same kind if they have similar lighting conditions or show similar patterns (e.g. sea floor, habitat types). Increase this number if you expect many different kinds of images. Lower the number to 1 if you have very few images and/or the content is largely uniform.
                </span>
            @endif
        </div>

        <div v-cloak v-show="showAdvanced" class="form-group{{ $errors->has('nd_patch_size') ? ' has-error' : '' }}">
            <label for="nd_patch_size">Patch size</label>
            <input type="number" class="form-control" name="nd_patch_size" id="nd_patch_size" value="{{ old('nd_patch_size', 39) }}" required min="3" max="99" step="2">
            @if($errors->has('nd_patch_size'))
               <span class="help-block">{{ $errors->first('nd_patch_size') }}</span>
            @else
                <span class="help-block">
                    Size in pixels of the image patches used to determine the training proposals. Increase the size if the images contain larger objects of interest, decrease the size if the objects are smaller. Larger patch sizes take longer to compute. Must be an odd number.
                </span>
            @endif
        </div>

        <div v-cloak v-show="showAdvanced">
            <div class="form-group{{ $errors->has('nd_threshold') ? ' has-error' : '' }}">
                <label for="nd_threshold">Threshold percentile</label>
                <input type="number" class="form-control" name="nd_threshold" id="nd_threshold" value="{{ old('nd_threshold', 99) }}" required min="0" max="99" step="1">
                @if($errors->has('nd_threshold'))
                   <span class="help-block">{{ $errors->first('nd_threshold') }}</span>
                @else
                    <span class="help-block">
                        Percentile of pixel saliency values used to determine the saliency threshold. Lower this value to get more training proposals. The default value should be fine for most cases.
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('nd_latent_size') ? ' has-error' : '' }}">
                <label for="nd_latent_size">Latent layer size</label>
                <input type="number" class="form-control" name="nd_latent_size" id="nd_latent_size" value="{{ old('nd_latent_size', 0.1) }}" required min="0.05" max="0.75" step="0.05">
                @if($errors->has('nd_latent_size'))
                   <span class="help-block">{{ $errors->first('nd_latent_size') }}</span>
                @else
                    <span class="help-block">
                        Learning capability used to determine training proposals. Increase this number to ignore more complex objects and patterns.
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('nd_trainset_size') ? ' has-error' : '' }}">
                <label for="nd_trainset_size">Number of training patches</label>
                <input type="number" class="form-control" name="nd_trainset_size" id="nd_trainset_size" value="{{ old('nd_trainset_size', 10000) }}" required min="1000" max="100000" step="1">
                @if($errors->has('nd_trainset_size'))
                   <span class="help-block">{{ $errors->first('nd_trainset_size') }}</span>
                @else
                    <span class="help-block">
                        Number of training image patches used to determine training proposals. You can increase this number for a large volume but it will take longer to compute.
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('nd_epochs') ? ' has-error' : '' }}">
                <label for="nd_epochs">Number of training epochs</label>
                <input type="number" class="form-control" name="nd_epochs" id="nd_epochs" value="{{ old('nd_epochs', 100) }}" required min="50" max="1000" step="1">
                @if($errors->has('nd_epochs'))
                   <span class="help-block">{{ $errors->first('nd_epochs') }}</span>
                @else
                    <span class="help-block">
                        Time spent on training when determining the training proposals.
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('nd_stride') ? ' has-error' : '' }}">
                <label for="nd_stride">Stride</label>
                <input type="number" class="form-control" name="nd_stride" id="nd_stride" value="{{ old('nd_stride', 2) }}" required min="1" max="10" step="1">
                @if($errors->has('nd_stride'))
                   <span class="help-block">{{ $errors->first('nd_stride') }}</span>
                @else
                    <span class="help-block">
                        A higher stride increases the speed of the novelty detection but reduces the sensitivity to small regions or objects.
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('nd_ignore_radius') ? ' has-error' : '' }}">
                <label for="nd_ignore_radius">Ignore radius</label>
                <input type="number" class="form-control" name="nd_ignore_radius" id="nd_ignore_radius" value="{{ old('nd_ignore_radius', 5) }}" required min="0" step="1">
                @if($errors->has('nd_ignore_radius'))
                   <span class="help-block">{{ $errors->first('nd_ignore_radius') }}</span>
                @else
                    <span class="help-block">
                        Ignore training proposals or annotation candidates which have a radius smaller or equal than this value in pixels.
                    </span>
                @endif
            </div>
        </div>
    </fieldset>

    <fieldset v-cloak v-if="useExistingAnnotations && showAdvanced">
        <legend>Existing Annotations <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}" title="More information on the configurable parameters for existing annotations" target="_blank"><i class="fas fa-info-circle"></i></a></legend>

        <div v-cloak class="form-group">
            <label for="restrict_labels">Restrict to labels</label>
            <loader :active="loading"></loader>
            <typeahead class="typeahead--block" :items="labels" placeholder="Label name" :clear-on-select="true" v-on:select="handleSelectedLabel"></typeahead>
            <span class="help-block">
                Only use existing annotations with these selected labels.
            </span>

            <ul class="list-group">
                <li v-for="label in selectedLabels" class="list-group-item">
                    <span class="label-color-dot" v-if="label.color" :style="{'background-color': '#' + label.color}" class="label-color"></span><span v-text="label.name"></span>
                    <button title="Remove label from selection" type="button" class="close" v-on:click="handleUnselectLabel(label)">&times;</button>
                </li>
                <li v-if="!hasSelectedLabels" class="list-group-item text-muted">No restriction</li>
            </ul>

            <input v-for="label in selectedLabels" type="hidden" name="restrict_labels[]" :value="label.id">
        </div>
    </fieldset>

    <fieldset v-cloak v-if="useKnowledgeTransfer">
        <legend v-if="showAdvanced">Knowledge Transfer <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['maia', 'knowledge-transfer'])}}" title="More information on the configurable parameters for knowledge transfer" target="_blank"><i class="fas fa-info-circle"></i></a></legend>

        <div class="form-group">
            <label for="kt_volume_id">Volume</label>
            <loader :active="loading"></loader>
            <div v-if="hasNoKnowledgeTransferVolumes" class="text-warning">
                No suitable volumes were found!
            </div>
            <typeahead class="typeahead--block" :items="knowledgeTransferVolumes" placeholder="Volume name" v-on:select="handleSelectedKnowledgeTransferVolume" :template="knowledgeTransferTypeaheadTemplate"></typeahead>
            <span class="help-block">
                The volume of which annotations should be used for knowledge transfer.
            </span>

            <input v-if="knowledgeTransferVolume" type="hidden" name="kt_volume_id" :value="knowledgeTransferVolume.id">
        </div>
    </fieldset>

    <fieldset v-cloak v-show="showAdvanced">
        <legend>Instance Segmentation <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}#configurable-parameters" title="More information on the configurable parameters for instance segmentation" target="_blank"><i class="fas fa-info-circle"></i></a></legend>

        <div class="form-group{{ $errors->has('is_train_scheme') ? ' has-error' : '' }}">
            <label for="is_train_scheme">Training scheme</label>
            <div class="row" v-for="(step, index) in trainScheme">
                <span class="col-xs-1">
                    <button type="button" class="btn btn-default" title="Remove this step from the scheme" v-on:click="removeTrainStep(index)"><i class="fa fa-minus"></i></button>
                </span>
                <span class="col-xs-3">
                    <select class="form-control" required v-model="step['layers']">
                        <option value="heads">heads</option>
                        <option value="all">all</option>
                    </select>
                </span>
                <span class="col-xs-4">
                    <input type="number" class="form-control" required min="1" step="1" v-model="step['epochs']">
                </span>
                <span class="col-xs-4">
                    <input type="number" class="form-control" required min="0.00001" max="1" step="0.00001" v-model="step['learning_rate']">
                </span>
            </div>
            <div class="row">
                <span class="col-xs-1">
                    <button type="button" class="btn btn-default" title="Add a new step to the scheme" v-on:click="addTrainStep"><i class="fa fa-plus"></i></button>
                </span>
                <span class="col-xs-3">
                    <select class="form-control" disabled></select>
                </span>
                <span class="col-xs-4">
                    <input type="number" class="form-control" disabled>
                </span>
                <span class="col-xs-4">
                    <input type="number" class="form-control" disabled>
                </span>
            </div>
            <div v-for="(step, index) in trainScheme">
                <input type="hidden" :name="`is_train_scheme[${index}][layers]`" :value="step['layers']">
                <input type="hidden" :name="`is_train_scheme[${index}][epochs]`" :value="step['epochs']">
                <input type="hidden" :name="`is_train_scheme[${index}][learning_rate]`" :value="step['learning_rate']">
            </div>
            @if($errors->has('is_train_scheme'))
               <span class="help-block">{{ $errors->first('is_train_scheme') }}</span>
            @else
                <span class="help-block">
                    The scheme to use for training of Mask R-CNN for instance segmentation. The learning rate should decrease with subsequent steps.
                </span>
            @endif
        </div>
    </fieldset>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <button v-on:click="toggle" type="button" class="btn btn-default"><span v-if="showAdvanced" v-cloak>Hide</span><span v-else>Show</span> advanced parameters</button>
        <button type="submit" class="btn btn-success pull-right" :disabled="canSubmit">Create job</button>
    </div>
</form>

@push('scripts')
<script type="text/javascript">
    biigle.$declare('maia.volumeId', {!! $volume->id !!});
    biigle.$declare('maia.trainScheme', {!! old('is_train_scheme', $defaultTrainScheme) !!});
    biigle.$declare('maia.hasErrors', {!! $errors->any() ? 'true' : 'false' !!});
    biigle.$declare('maia.trainingDataMethod', '{!! old('training_data_method', 'novelty_detection') !!}');
</script>
@endpush
