<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAnnotationCandidates extends FormRequest
{
    /**
     * The job to submit candidates of
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

        return $this->user()->can('update', $this->job);
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
            if ($this->job->state_id !== State::annotationCandidatesId()) {
                $validator->errors()->add('id', 'Annotation candidates can only be submitted if the job is in annotation candidates state.');
            }

            if ($this->job->convertingCandidates) {
                $validator->errors()->add('id', 'A job to convert annotation candidates is currently in progress. Please try again.');
            }
        });
    }
}
