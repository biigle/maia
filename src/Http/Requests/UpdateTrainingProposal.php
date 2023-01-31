<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\TrainingProposal;
use Exception;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingProposal extends FormRequest
{
    /**
     * The training proposal to update
     *
     * @var TrainingProposal
     */
    public $proposal;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->proposal = TrainingProposal::findOrFail($this->route('id'));

        return $this->user()->can('update', $this->proposal);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'selected' => 'required_without:points|boolean',
            'points' => 'required_without:selected|array',
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
            $this->maybeValidatePoints($validator);
            $this->maybeRestrictUpdating($validator);
        });
    }

    /**
     * Check if the points array is correct if it is present.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    protected function maybeValidatePoints($validator)
    {
        if ($this->filled('points')) {
            try {
                $this->proposal->validatePoints($this->input('points'));
            } catch (Exception $e) {
                $validator->errors()->add('points', $e->getMessage());
            }
        }
    }

    /**
     * Do not allow updating of training proposals if the MAIA job is running object
     * detection or is finished.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    protected function maybeRestrictUpdating($validator)
    {
        $jobContinued = !$this->proposal->job()
            ->where('state_id', State::trainingProposalsId())
            ->exists();

        if ($jobContinued) {
            $validator->errors()->add('selected', 'Training proposals cannot be updated after they have been submitted.');
        }
    }
}
