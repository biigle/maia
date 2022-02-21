<?php

namespace Biigle\Modules\Maia\Database\Factories;

use Biigle\Modules\Maia\MaiaJobState;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaiaJobStateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MaiaJobState::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->username(),
        ];
    }
}
