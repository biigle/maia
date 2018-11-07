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
            'clusters' => 'required|integer|min:1|max:100',
            'patch_size' => ['required', 'integer', 'min:3', 'max:99', new OddNumber],
            'threshold' => 'required|integer|min:0|max:99',
            'latent_size' => 'required|numeric|min:0.05|max:0.75',
            'trainset_size' => 'required|integer|min:1000|max:100000',
            'epochs' => 'required|integer|min:50|max:1000',
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
            $hasUnfinishedJob = MaiaJob::where('volume_id', $this->volume->id)
                ->where('state_id', '!=', State::finishedId())
                ->exists();

            if ($hasUnfinishedJob) {
                $validator->errors()->add('clusters', 'A new MAIA job can only be sumbitted if there are no other unfinished jobs for the same volume.');
            }
        });
    }
}
