<?php

$factory->define(Biigle\Modules\Maia\AnnotationCandidate::class, function ($faker) {
    return [
        'image_id' => function () {
            return factory(Biigle\Image::class)->create()->id;
        },
        'shape_id' => Biigle\Shape::circleId(),
        'job_id' => function () {
            return factory(Biigle\Modules\Maia\MaiaJob::class)->create()->id;
        },
        'score' => $faker->randomNumber(),
        'label_id' => null,
        'annotation_id' => null,
    ];
});
