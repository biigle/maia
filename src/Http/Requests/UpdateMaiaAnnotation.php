<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Exception;
use Biigle\Modules\Maia\MaiaAnnotation;
use Illuminate\Foundation\Http\FormRequest;

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
        if ($this->filled('points')) {
            $validator->after(function ($validator) {
                try {
                    $this->annotation->validatePoints($this->input('points'));
                } catch (Exception $e) {
                    $validator->errors()->add('points', $e->getMessage());
                }
            });
        }
    }
}
