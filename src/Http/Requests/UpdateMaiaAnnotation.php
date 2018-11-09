<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Exception;
use Biigle\Modules\Maia\MaiaAnnotation;
use Illuminate\Foundation\Http\FormRequest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\MaiaAnnotationType as Type;

class UpdateMaiaAnnotation extends FormRequest
{
    /**
     * The annotation to update
     *
     * @var MaiaAnnotation
     */
    public $annotation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->annotation = MaiaAnnotation::findOrFail($this->route('id'));

        return $this->user()->can('update', $this->annotation);
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
            $this->maybeRestrictUpdatingTrainingProposals($validator);
            $this->maybeRestrictUpdatingAnnotationCandidates($validator);
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
                $this->annotation->validatePoints($this->input('points'));
            } catch (Exception $e) {
                $validator->errors()->add('points', $e->getMessage());
            }
        }
    }

    /**
     * Do not allow updating of training proposal if the MAIA job is running instance
     * segmentation or is finished.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    protected function maybeRestrictUpdatingTrainingProposals($validator)
    {
        if ($this->annotation->type_id === Type::trainingProposalId()) {
            $jobContinued = !$this->annotation->job()
                ->where('state_id', State::trainingProposalsId())
                ->exists();

            if ($jobContinued) {
                $validator->errors()->add('selected', 'Training proposals cannot be updated after they have been submitted.');
            }
        }
    }

    /**
     * Do not allow updating of annotation candidate if it already has been selected.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    protected function maybeRestrictUpdatingAnnotationCandidates($validator)
    {
        if ($this->annotation->selected && $this->annotation->type_id === Type::annotationCandidateId()) {
            $validator->errors()->add('selected', 'Selected annotation candidates cannot be modified.');
        }
    }
}
