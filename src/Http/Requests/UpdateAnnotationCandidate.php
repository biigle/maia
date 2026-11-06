<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Biigle\Http\Requests\Traits\ValidatesAnnotationGuideline;
use Biigle\Label;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Rules\AnnotationPoints;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnotationCandidate extends FormRequest
{
    use ValidatesAnnotationGuideline;

    /**
     * The annotation candidate to update
     *
     * @var AnnotationCandidate
     */
    public $candidate;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->candidate = AnnotationCandidate::findOrFail($this->route('id'));

        if ($this->filled('label_id')) {
            $label = Label::find($this->input('label_id'));

            if (!is_null($label)) {
                return $this->user()->can('update', $this->candidate)
                    && $this->user()->can('attach-label', [$this->candidate, $label]);
            }
        }


        return $this->user()->can('update', $this->candidate);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label_id' => 'nullable|integer|exists:labels,id',
            'points' => [
                'array',
                new AnnotationPoints($this->candidate->shape->value),
            ],
            'guideline_id' => 'nullable|integer',
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
            $this->maybeRestrictUpdating($validator);
            $this->validateLabelGuideline($validator);
        });
    }

    /**
     * Validate the annotation guideline if a label should be attached. Detaching a label
     * or updating the points is always allowed because the shape does not change.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    protected function validateLabelGuideline($validator)
    {
        if (!$this->filled('label_id') || $validator->errors()->isNotEmpty()) {
            return;
        }

        $guideline = $this->validateGuideline(
            $validator,
            $this->candidate->job->volume_id,
            $this->integer('guideline_id') ?: null
        );

        if (is_null($guideline)) {
            return;
        }

        $labelId = $this->integer('label_id');
        $this->validateGuidelineLabel($validator, $guideline, $labelId);
        $this->validateGuidelineShape(
            $validator,
            $guideline,
            $this->candidate->shape,
            $labelId,
            'label_id'
        );
    }

    /**
     * Do not allow updating of candidate candidate if it already has been selected.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    protected function maybeRestrictUpdating($validator)
    {
        if (!is_null($this->candidate->annotation_id)) {
            if ($this->has('points')) {
                $validator->errors()->add('points', 'A converted annotation candidate can no longer be updated');
            } else {
                $validator->errors()->add('label_id', 'A converted annotation candidate can no longer be updated');
            }
        }
    }
}
