<?php

namespace Biigle\Modules\Maia\Database\Factories;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnotationCandidateFeatureVectorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AnnotationCandidateFeatureVector::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => AnnotationCandidate::factory(),
            'job_id' => MaiaJob::factory(),
            'vector' => range(0, 383),
        ];
    }
}
