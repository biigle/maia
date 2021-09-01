<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Biigle\Label;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\MaiaJobState as State;
use Exception;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnotationCandidate extends FormRequest
{
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
            'points' => 'array',
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
                $this->candidate->validatePoints($this->input('points'));
            } catch (Exception $e) {
                $validator->errors()->add('points', $e->getMessage());
            }
        }
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
