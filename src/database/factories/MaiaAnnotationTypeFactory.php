<?php

$factory->define(Biigle\Modules\Maia\MaiaAnnotationType::class, function ($faker) {
    return [
        'name' => $faker->username(),
    ];
});
