<?php

namespace Biigle\Modules\Maia\Database\Factories;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState;
use Biigle\User;
use Biigle\Volume;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaiaJobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MaiaJob::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'volume_id' => Volume::factory(),
            'user_id' => User::factory(),
            'state_id' => function () {
                return MaiaJobState::noveltyDetectionId();
            },
        ];
    }
}
