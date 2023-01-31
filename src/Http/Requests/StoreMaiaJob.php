<?php

namespace Biigle\Modules\Maia\Http\Requests;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Rules\AreaKnowledgeTransferVolume;
use Biigle\Modules\Maia\Rules\KnowledgeTransferVolume;
use Biigle\Modules\Maia\Rules\OddNumber;
use Biigle\Modules\Maia\Traits\QueriesExistingAnnotations;
use Biigle\Volume;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaiaJob extends FormRequest
{
    use QueriesExistingAnnotations;

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

        if (config('maia.maintenance_mode')) {
            return $this->user()->can('sudo');
        }

        return $this->user()->can('edit-in', $this->volume);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'training_data_method' => 'required|in:novelty_detection,own_annotations,knowledge_transfer,area_knowledge_transfer',

            'nd_clusters' => 'required_if:training_data_method,novelty_detection|integer|min:1|max:100',
            'nd_patch_size' => ['required_if:training_data_method,novelty_detection', 'integer', 'min:3', 'max:99', new OddNumber],
            'nd_threshold' => 'required_if:training_data_method,novelty_detection|integer|min:0|max:99',
            'nd_latent_size' => 'required_if:training_data_method,novelty_detection|numeric|min:0.05|max:0.75',
            'nd_trainset_size' => 'required_if:training_data_method,novelty_detection|integer|min:1000|max:100000',
            'nd_epochs' => 'required_if:training_data_method,novelty_detection|integer|min:50|max:1000',
            'nd_stride' => 'required_if:training_data_method,novelty_detection|integer|min:1|max:10',
            'nd_ignore_radius' => 'required_if:training_data_method,novelty_detection|integer|min:0',

            'oa_restrict_labels' => 'array',
            'oa_restrict_labels.*' => 'integer|exists:labels,id',
            'oa_show_training_proposals' => 'boolean',

            'kt_restrict_labels.*' => 'integer|exists:labels,id',
        ];

        if ($this->input('training_data_method') === 'knowledge_transfer') {
            $rules['kt_volume_id'] = ['required', 'integer', 'exists:volumes,id', new KnowledgeTransferVolume];
        } elseif ($this->input('training_data_method') === 'area_knowledge_transfer') {
            $rules['kt_volume_id'] = ['required', 'integer', 'exists:volumes,id', new AreaKnowledgeTransferVolume];
        }

        return $rules;
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
            if (!$this->volume->isImageVolume()) {
                $validator->errors()->add('volume', 'MAIA is only available for image volumes.');
            }

            $hasJobInProgress = MaiaJob::where('volume_id', $this->volume->id)
                ->whereIn('state_id', [
                    State::noveltyDetectionId(),
                    State::trainingProposalsId(),
                    State::objectDetectionId(),
                ])
                ->exists();

            if ($hasJobInProgress) {
                $validator->errors()->add('volume', 'A new MAIA job can only be sumbitted if there are no other jobs in progress for the same volume.');
            }

            if ($this->volume->hasTiledImages()) {
                $validator->errors()->add('volume', 'New MAIA jobs cannot be created for volumes with very large images.');
            }

            if ($this->hasSmallImages($this->volume)) {
                $validator->errors()->add('volume', 'New MAIA jobs cannot be created for volumes that contain images smaller than 512 pixels on one edge.');
            }

            if ($this->input('training_data_method') === MaiaJob::TRAIN_NOVELTY_DETECTION && $this->volume->images()->count() < $this->input('nd_clusters')) {
                $validator->errors()->add('nd_clusters', 'The number of image clusters must not be greater than the number of images in the volume.');
            }

            if ($this->input('training_data_method') === MaiaJob::TRAIN_OWN_ANNOTATIONS && $this->hasNoExistingAnnotations()) {
                $validator->errors()->add('training_data_method', 'There are no existing annotations (with the chosen labels) in this volume.');
            }

            if (in_array($this->input('training_data_method'), [MaiaJob::TRAIN_KNOWLEDGE_TRANSFER, MaiaJob::TRAIN_AREA_KNOWLEDGE_TRANSFER]) && $this->hasNoKnowledgeTransferAnnotations()) {
                $validator->errors()->add('training_data_method', 'There are no existing annotations (with the chosen labels) in the volume chosen for knowledge transfer.');
            }
        });
    }

    /**
     * Determine if there are existing annotations that can be used as training data.
     *
     * @return boolean
     */
    protected function hasNoExistingAnnotations()
    {
        $restrictLabels = $this->input('oa_restrict_labels', []);

        return !$this->getExistingAnnotationsQuery($this->volume->id, $restrictLabels)->exists();
    }

    /**
     * Determine if there are existing annotations in the volume chosen for knowledge transfer.
     *
     * @return boolean
     */
    protected function hasNoKnowledgeTransferAnnotations()
    {
        $restrictLabels = $this->input('kt_restrict_labels', []);

        return !$this->getExistingAnnotationsQuery($this->input('kt_volume_id'), $restrictLabels)->exists();
    }

    /**
     * Determine whether the volume contains images smaller than 512px.
     *
     * @param Volume $volume
     *
     * @return boolean
     */
    protected function hasSmallImages(Volume $volume)
    {
        return $volume->images()
            ->where(function ($query) {
                $query->whereNull('attrs->width')
                ->orWhereNull('attrs->height')
                ->orWhereRaw('("attrs"->>\'width\')::int < 512')
                ->orWhereRaw('("attrs"->>\'height\')::int < 512');
            })
            ->exists();
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        if (config('maia.maintenance_mode')) {
            throw new AuthorizationException('MAIA is in maintenance mode and no new jobs can be submitted.');
        }

        return parent::failedAuthorization();
    }
}
