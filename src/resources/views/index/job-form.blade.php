<form id="maia-job-form" method="POST" action="{{ url("api/v1/volumes/{$volume->id}/maia-jobs") }}">
    <legend>Create a new MAIA job</legend>
    <div class="form-group{{ $errors->has('clusters') ? ' has-error' : '' }}">
        <label for="clusters">Number of image clusters</label>
        <input type="number" class="form-control" name="clusters" id="clusters" value="{{ old('clusters', 5) }}" required min="1" max="100" step="1">
        @if($errors->has('clusters'))
           <span class="help-block">{{ $errors->first('clusters') }}</span>
        @else
            <span class="help-block">
                Number of different kinds of images to expect. Images are of the same kind if they have similar lighting conditions or show similar patterns (e.g. sea floor, habitat types). Increase this number if you expect many different kinds of images. Lower the number to 1 if you have very few images and/or the content is largely uniform.
            </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('patch_size') ? ' has-error' : '' }}">
        <label for="patch_size">Patch size</label>
        <input type="number" class="form-control" name="patch_size" id="patch_size" value="{{ old('patch_size', 39) }}" required min="3" max="99" step="2">
        @if($errors->has('patch_size'))
           <span class="help-block">{{ $errors->first('patch_size') }}</span>
        @else
            <span class="help-block">
                Size in pixels of the image patches used determine the training proposals. Increase the size if the images contain larger objects of interest, decrease the size if the objects are smaller. Larger patch sizes take longer to compute. Must be an odd number.
            </span>
        @endif
    </div>

    <fieldset v-cloak v-show="showAdvanced">
        <div class="form-group{{ $errors->has('threshold') ? ' has-error' : '' }}">
            <label for="threshold">Threshold percentile</label>
            <input type="number" class="form-control" name="threshold" id="threshold" value="{{ old('threshold', 99) }}" required min="0" max="99" step="1">
            @if($errors->has('threshold'))
               <span class="help-block">{{ $errors->first('threshold') }}</span>
            @else
                <span class="help-block">
                    Percentile of pixel saliency values used to determine the saliency threshold. Lower this value to get more training proposals. The default value should be fine for most cases.
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('latent_size') ? ' has-error' : '' }}">
            <label for="latent_size">Latent layer size</label>
            <input type="number" class="form-control" name="latent_size" id="latent_size" value="{{ old('latent_size', 0.1) }}" required min="0.05" max="0.75" step="0.05">
            @if($errors->has('latent_size'))
               <span class="help-block">{{ $errors->first('latent_size') }}</span>
            @else
                <span class="help-block">
                    Learning capability used to determine training proposals. Increase this number to ignore more complex objects and patterns.
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('trainset_size') ? ' has-error' : '' }}">
            <label for="trainset_size">Number of training patches</label>
            <input type="number" class="form-control" name="trainset_size" id="trainset_size" value="{{ old('trainset_size', 10000) }}" required min="1000" max="100000" step="1">
            @if($errors->has('trainset_size'))
               <span class="help-block">{{ $errors->first('trainset_size') }}</span>
            @else
                <span class="help-block">
                    Number of training image patches used to determine training proposals. You can increase this number for a large volume but it will take longer to compute.
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('epochs') ? ' has-error' : '' }}">
            <label for="epochs">Number of training epochs</label>
            <input type="number" class="form-control" name="epochs" id="epochs" value="{{ old('epochs', 100) }}" required min="50" max="1000" step="1">
            @if($errors->has('epochs'))
               <span class="help-block">{{ $errors->first('epochs') }}</span>
            @else
                <span class="help-block">
                    Time spent on training when determining the training proposals.
                </span>
            @endif
        </div>
    </fieldset>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <button v-on:click="toggle" type="button" class="btn btn-default"><span v-if="showAdvanced" v-cloak>Hide</span><span v-else>Show</span> advanced settings</button>
        <button type="submit" class="btn btn-success pull-right">Create job</button>
    </div>
</form>
