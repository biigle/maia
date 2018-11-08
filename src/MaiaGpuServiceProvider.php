<?php

namespace Biigle\Modules\Maia;

use Illuminate\Support\ServiceProvider;

class MaiaGpuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/maia.php', 'maia');
    }
}
