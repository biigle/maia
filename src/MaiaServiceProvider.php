<?php

namespace Biigle\Modules\Maia;

use Event;
use Biigle\Services\Modules;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Events\MaiaJobDeleted;
use Biigle\Modules\Maia\Listeners\DeleteAnnotationPatches;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Biigle\Modules\Maia\Listeners\DispatchNoveltyDetectionRequest;

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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'maia');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->publishes([
            __DIR__.'/public/assets' => public_path('vendor/maia'),
        ], 'public');

        $router->group([
            'namespace' => 'Biigle\Modules\Maia\Http\Controllers',
            'middleware' => 'web',
        ], function ($router) {
            require __DIR__.'/Http/routes.php';
        });

        $modules->register('maia', [
            'viewMixins' => [
                'volumesSidebar',
            ],
            // 'controllerMixins' => [
            //     //
            // ],
        ]);

        Gate::policy(MaiaJob::class, Policies\MaiaJobPolicy::class);
        Event::listen(MaiaJobCreated::class, DispatchNoveltyDetectionRequest::class);
        Event::listen(MaiaJobDeleted::class, DeleteAnnotationPatches::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/maia.php', 'maia');

        $this->app->singleton('command.maia.publish', function ($app) {
            return new \Biigle\Modules\Maia\Console\Commands\Publish;
        });
        $this->commands('command.maia.publish');

        if (config('app.env') === 'testing') {
            $this->registerEloquentFactoriesFrom(__DIR__.'/database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.maia.publish',
        ];
    }

    /**
     * Register factories.
     *
     * @param  string  $path
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path)
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}
