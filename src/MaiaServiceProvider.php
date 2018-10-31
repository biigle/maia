<?php

namespace Biigle\Modules\Maia;

use Biigle\Services\Modules;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class MaiaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @param  \Biigle\Services\Modules  $modules
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
     */
    public function boot(Modules $modules, Router $router)
    {
        // $this->loadViewsFrom(__DIR__.'/resources/views', 'maia');

        // $this->publishes([
        //     __DIR__.'/public/assets' => public_path('vendor/maia'),
        // ], 'public');

        // $router->group([
        //     'namespace' => 'Biigle\Modules\Maia\Http\Controllers',
        //     'middleware' => 'web',
        // ], function ($router) {
        //     require __DIR__.'/Http/routes.php';
        // });

        // $modules->register('maia', [
        //     'viewMixins' => [
        //         //
        //     ],
        //     'controllerMixins' => [
        //         //
        //     ],
        // ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton('command.maia.publish', function ($app) {
        //     return new \Biigle\Modules\Maia\Console\Commands\Publish;
        // });
        // $this->commands('command.maia.publish');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // return [
        //     'command.maia.publish',
        // ];
    }
}
