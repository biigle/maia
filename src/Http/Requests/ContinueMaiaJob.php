<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Illuminate\Foundation\Http\FormRequest;

class ContinueMaiaJob extends FormRequest
{
    /**
     * The job to update
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
            if ($this->job->state_id !== State::trainingProposalsId()) {
                $validator->errors()->add('id', 'The job cannot continue if it is not in training proposal selection and refinement state.');
            } elseif (!$this->job->trainingProposals()->selected()->exists()) {
                $validator->errors()->add('id', 'The job cannot continue if it has no selected training proposals.');
            }
        });
    }
}
