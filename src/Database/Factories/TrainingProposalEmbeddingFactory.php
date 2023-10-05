<?php

namespace Biigle\Modules\Maia\Database\Factories;

use Biigle\Image;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposalEmbedding;
use Biigle\Shape;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingProposalEmbeddingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrainingProposalEmbedding::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomDigit(),
            'embedding' => range(0, 383),
        ];
    }
}
