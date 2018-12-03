<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Biigle\Volume;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\Rules\OddNumber;
use Illuminate\Foundation\Http\FormRequest;
use Biigle\Modules\Maia\MaiaJobState as State;

class StoreMaiaJob extends FormRequest
{
    /**
     * The volume to create the MAIA job for.
     *
     * @var Volume
     */
    public $volume;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->volume = Volume::findOrFail($this->route('id'));

        return $this->user()->can('edit-in', $this->volume);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nd_clusters' => 'required|integer|min:1|max:100',
            'nd_patch_size' => ['required', 'integer', 'min:3', 'max:99', new OddNumber],
            'nd_threshold' => 'required|integer|min:0|max:99',
            'nd_latent_size' => 'required|numeric|min:0.05|max:0.75',
            'nd_trainset_size' => 'required|integer|min:1000|max:100000',
            'nd_epochs' => 'required|integer|min:50|max:1000',
            'nd_stride' => 'required|integer|min:1|max:10',
            'is_epochs_head' => 'required|integer|min:1',
            'is_epochs_all' => 'required|integer|min:1',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasJobInProgress = MaiaJob::where('volume_id', $this->volume->id)
                ->whereIn('state_id', [
                    State::noveltyDetectionId(),
                    State::trainingProposalsId(),
                    State::instanceSegmentationId(),
                ])
                ->exists();

            if ($hasJobInProgress) {
                $validator->errors()->add('clusters', 'A new MAIA job can only be sumbitted if there are no other jobs in progress for the same volume.');
            }

            if ($this->volume->hasTiledImages()) {
                $validator->errors()->add('volume', 'New MAIA jobs cannot be created for volumes with very large images.');
            }
        });
    }
}
