<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\MaiaJobState as State;

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
        // TODO candidates that have been converted cannot be updated
    }
}
