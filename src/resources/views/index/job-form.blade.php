<h3>Create a new MAIA job <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['maia', 'about'])}}" title="More information on MAIA" target="_blank"><i class="fas fa-info-circle"></i></a></h3>
<p>
    A job can run for many hours or even a day. Please choose your parameters carefully before you submit a new job.
</p>
<form id="maia-job-form" method="POST" action="{{ url("api/v1/volumes/{$volume->id}/maia-jobs") }}" v-on:submit="submit">
    <fieldset>
        <legend v-cloak v-show="showAdvanced">Novelty Detection <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}#configurable-parameters" title="More information on the configurable parameters for novelty detection" target="_blank"><i class="fas fa-info-circle"></i></a></legend>
        <div v-show="!skipNoveltyDetection" class="form-group{{ $errors->has('nd_clusters') ? ' has-error' : '' }}">
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

        <div v-show="!skipNoveltyDetection" class="form-group{{ $errors->has('nd_patch_size') ? ' has-error' : '' }}">
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

        <div class="form-group{{ $errors->has('use_existing') ? ' has-error' : '' }}">
            <label for="use_existing">
                <input type="checkbox" name="use_existing" id="use_existing" v-model="useExistingAnnotations" value="1">
                Use existing annotations <loader v-cloak :active="loading"></loader>
            </label>
            @if($errors->has('use_existing'))
               <span class="help-block">{{ $errors->first('use_existing') }}</span>
            @else
                <span class="help-block">
                    Use existing annotations as training proposals. All annotations will be converted to circles.
                </span>
            @endif
            <p v-cloak v-show="hasNoExistingAnnotations" class="text-warning">
                There are no existing annotations.
            </p>
        </div>

        <div v-cloak v-show="showRestrictLabelsInput" class="form-group">
            <label for="restrict_labels">Restrict to labels</label>
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

        <div v-show="canSkipNoveltyDetection" v-cloak class="form-group{{ $errors->has('skip_nd') ? ' has-error' : '' }}">
            <label for="skip_nd">
                <input type="checkbox" name="skip_nd" id="skip_nd" v-model="skipNoveltyDetection" value="1">
                Skip novelty detection
            </label>
            @if($errors->has('skip_nd'))
               <span class="help-block">{{ $errors->first('skip_nd') }}</span>
            @else
                <span class="help-block">
                    You can choose to skip the novelty detection stage if you use existing annotations as training proposals. Make sure that you have enough training proposals in this case.
                </span>
            @endif
        </div>


        <div v-cloak v-show="showAdvanced">
            <div v-show="!skipNoveltyDetection" class="form-group{{ $errors->has('nd_threshold') ? ' has-error' : '' }}">
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

            <div v-show="!skipNoveltyDetection" class="form-group{{ $errors->has('nd_latent_size') ? ' has-error' : '' }}">
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

            <div v-show="!skipNoveltyDetection" class="form-group{{ $errors->has('nd_trainset_size') ? ' has-error' : '' }}">
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

            <div v-show="!skipNoveltyDetection" class="form-group{{ $errors->has('nd_epochs') ? ' has-error' : '' }}">
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

            <div v-show="!skipNoveltyDetection" class="form-group{{ $errors->has('nd_stride') ? ' has-error' : '' }}">
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

            <div v-show="!skipNoveltyDetection" class="form-group{{ $errors->has('nd_ignore_radius') ? ' has-error' : '' }}">
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
    <fieldset>
        <div v-cloak v-show="showAdvanced">
            <legend>Instance Segmentation <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}#configurable-parameters" title="More information on the configurable parameters for instance segmentation" target="_blank"><i class="fas fa-info-circle"></i></a></legend>
            <div class="form-group{{ $errors->has('is_epochs_head') ? ' has-error' : '' }}">
                <label for="is_epochs_head">Number of training epochs (head)</label>
                <input type="number" class="form-control" name="is_epochs_head" id="is_epochs_head" value="{{ old('is_epochs_head', 20) }}" required min="1" step="1">
                @if($errors->has('is_epochs_head'))
                   <span class="help-block">{{ $errors->first('is_epochs_head') }}</span>
                @else
                    <span class="help-block">
                        Time spent on training only the head layers of Mask R-CNN for instance segmentation. This is faster and should be a higher number than epochs (all).
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('is_epochs_all') ? ' has-error' : '' }}">
                <label for="is_epochs_all">Number of training epochs (all)</label>
                <input type="number" class="form-control" name="is_epochs_all" id="is_epochs_all" value="{{ old('is_epochs_all', 10) }}" required min="1" step="1">
                @if($errors->has('is_epochs_all'))
                   <span class="help-block">{{ $errors->first('is_epochs_all') }}</span>
                @else
                    <span class="help-block">
                        Time spent on training all layers of Mask R-CNN for instance segmentation. This is slower and should be a lower number than epochs (head).
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('is_store_model') ? ' has-error' : '' }}">
            <label for="is_store_model">
                <input type="checkbox" name="is_store_model" id="is_store_model" v-model="storeModel" value="1">
                Store trained model
            </label>
            @if($errors->has('is_store_model'))
               <span class="help-block">{{ $errors->first('is_store_model') }}</span>
            @else
                <span class="help-block">
                    Store the trained instance segmentation model to reuse it for another MAIA job.
                </span>
            @endif
        </div>

        <div v-cloak v-if="storeModel" class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label for="description">Description</label>
            <input type="text" class="form-control" name="description" id="description" value="{{ old('description') }}" required placeholder="Detection of Kolga Hyalina in {{$volume->name}}">
            @if($errors->has('description'))
               <span class="help-block">{{ $errors->first('description') }}</span>
            @else
                <span class="help-block">
                    Description for this job so the stored instance segmentation model can be found later.
                </span>
            @endif
        </div>
    </fieldset>


    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <button v-on:click="toggle" type="button" class="btn btn-default"><span v-if="showAdvanced" v-cloak>Hide</span><span v-else>Show</span> advanced parameters</button>
        <button type="submit" class="btn btn-success pull-right" :disabled="submitted">Create job</button>
    </div>
</form>

@push('styles')
<link href="{{ cachebust_asset('vendor/label-trees/styles/main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ cachebust_asset('vendor/label-trees/scripts/main.js') }}"></script>
<script type="text/javascript">
    biigle.$declare('maia.volumeId', {!! $volume->id !!});
    biigle.$declare('maia.useExistingAnnotations', {!! old('use_existing') ? 'true' : 'false' !!});
    biigle.$declare('maia.skipNoveltyDetection', {!! old('skip_nd') ? 'true' : 'false' !!});
    biigle.$declare('maia.storeModel', {!! old('is_store_model') ? 'true' : 'false' !!});
</script>
@endpush
