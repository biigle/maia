<?php

$factory->define(Biigle\Modules\Maia\MaiaJob::class, function ($faker) {
    return [
        'volume_id' => function () {
            return factory(Biigle\Volume::class)->create()->id;
        },
        'user_id' => function () {
            return factory(Biigle\User::class)->create()->id;
        },
        'state_id' => Biigle\Modules\Maia\MaiaJobState::noveltyDetectionId(),
        'has_model' => false,
        'description' => $faker->text(),
    ];
});
