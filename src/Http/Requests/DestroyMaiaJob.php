<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Illuminate\Foundation\Http\FormRequest;

class DestroyMaiaJob extends FormRequest
{
    /**
     * The job to destroy
     *
     * @var MaiaJob
     */
    public $job;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->job = MaiaJob::findOrFail($this->route('id'));

        return $this->user()->can('destroy', $this->job);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
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
            if ($this->job->state_id === State::noveltyDetectionId()) {
                $validator->errors()->add('id', 'The job cannot be deleted while the novelty detection is running.');
            } elseif ($this->job->state_id === State::objectDetectionId()) {
                $validator->errors()->add('id', 'The job cannot be deleted while the object detection is running.');
            }
        });
    }
}
