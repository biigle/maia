<?php

namespace Biigle\Modules\Maia\Database\Factories;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingProposalFeatureVectorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrainingProposalFeatureVector::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomDigit(),
            'job_id' => MaiaJob::factory(),
            'vector' => range(0, 383),
        ];
    }
}
