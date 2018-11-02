<?php

$factory->define(Biigle\Modules\Maia\MaiaJobState::class, function ($faker) {
    return [
        'name' => $faker->username(),
    ];
});
