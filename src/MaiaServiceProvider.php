<?php

namespace Biigle\Modules\Maia;

use Biigle\Http\Requests\UpdateUserSettings;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\Listeners\DispatchInstanceSegmentationRequest;
use Biigle\Modules\Maia\Listeners\DispatchMaiaJob;
use Biigle\Modules\Maia\Listeners\PrepareDeleteAnnotationPatches;
use Biigle\Modules\Maia\Listeners\PruneTrainingProposalPatches;
use Biigle\Services\Modules;
use Event;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
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
                'manualTutorial',
                'manualReferences',
            ],
            'apidoc' => [__DIR__.'/Http/Controllers/Api/'],
        ]);

        if (config('maia.notifications.allow_user_settings')) {
            $modules->registerViewMixin('maia', 'settings.notifications');
            UpdateUserSettings::addRule('maia_notifications', 'filled|in:email,web');
        }

        Gate::policy(MaiaJob::class, Policies\MaiaJobPolicy::class);
        Gate::policy(TrainingProposal::class, Policies\TrainingProposalPolicy::class);
        Gate::policy(AnnotationCandidate::class, Policies\AnnotationCandidatePolicy::class);
        Event::listen(MaiaJobCreated::class, DispatchMaiaJob::class);
        Event::listen(MaiaJobContinued::class, DispatchInstanceSegmentationRequest::class);
        Event::listen(MaiaJobContinued::class, PruneTrainingProposalPatches::class);
        Event::listen(MaiaJobDeleting::class, PrepareDeleteAnnotationPatches::class);
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

        $this->app->singleton('command.maia.migrate-patch-storage', function ($app) {
            return new \Biigle\Modules\Maia\Console\Commands\MigratePatchStorage;
        });
        $this->commands('command.maia.migrate-patch-storage');

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
            'command.maia.migrate-patch-storage',
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
