<?php

$factory->define(Biigle\Modules\Maia\MaiaAnnotation::class, function ($faker) {
    return [
        'image_id' => function () {
            return factory(Biigle\Image::class)->create()->id;
        },
        'shape_id' => Biigle\Shape::$circleId,
        'type_id' => function () {
            return factory(Biigle\Modules\Maia\MaiaAnnotationType::class)->create()->id;
        },
        'job_id' => function () {
            return factory(Biigle\Modules\Maia\MaiaJob::class)->create()->id;
        },
        'score' => $faker->randomFloat(),
        'selected' => false,
    ];
});
