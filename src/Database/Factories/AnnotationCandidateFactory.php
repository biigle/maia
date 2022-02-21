<?php

namespace Biigle\Modules\Maia\Database\Factories;

use Biigle\Image;
use Biigle\ImageAnnotation;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Shape;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnotationCandidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AnnotationCandidate::class;

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
            'label_id' => null,
            'annotation_id' => null,
        ];
    }
}
