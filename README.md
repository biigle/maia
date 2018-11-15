# BIIGLE MAIA Module

Perform the Machine Learning Assisted Image Annotation method in BIIGLE.

## Installation

Install the module:

```bash
composer config repositories.maia vcs https://github.com/biigle/maia
composer require biigle/maia
```

Take a look at the [`requirements.txt`](requirements.txt) for the Python dependencies of this module. Install the requirements with `pip install -r requirements.txt`.

### In your BIIGLE application instance

1. Add `Biigle\Modules\Maia\MaiaServiceProvider::class` to the `providers` array in `config/app.php`.
2. Run `php artisan maia:publish` to refresh the public assets of this package. Do this for every update of the package.

### If you use biigle/gpu-server on a remote machine

Add the following repositories to your `composer.json`:

```bash
composer config repositories.label-trees vcs https://github.com/biigle/label-trees
composer config repositories.projects vcs https://github.com/biigle/projects
composer config repositories.volumes vcs https://github.com/biigle/volumes
composer config repositories.largo vcs https://github.com/biigle/largo
composer config repositories.annotations vcs https://github.com/biigle/annotations
```

Add `$app->register(Biigle\Modules\Maia\MaiaGpuServiceProvider::class);` to `bootstrap/app.php`.

## Configuration

New processing jobs are submitted to the `default` queue of the `gpu` connection. You can configure these with the `MAIA_REQUEST_QUEUE` and `MAIA_REQUEST_CONNECTION` environment variables.

The results of the processing jobs are submitted to the `default` queue of the `gpu-response` connection. You can configure these with the `MAIA_RESPONSE_QUEUE` and `MAIA_RESPONSE_CONNECTION` environment variables.
