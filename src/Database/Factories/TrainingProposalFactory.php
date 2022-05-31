<?php

namespace Biigle\Modules\Maia\Database\Factories;

use Biigle\Image;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Shape;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingProposalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrainingProposal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image_id' => Image::factory(),
            'shape_id' => function () {
                return Shape::circleId();
            },
            'job_id' => MaiaJob::factory(),
            'score' => $this->faker->randomNumber(),
            'selected' => false,
            'label_id' => null,
        ];
    }
}
