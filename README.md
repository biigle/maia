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
3. Run `php artisan migrate` to create the new database tables.
4. Configure a storage disk each for the training proposal and annotation candidate patches. Set the `MAIA_TRAINING_PROPOSAL_STORAGE_DISK` and `MAIA_ANNOTATION_CANDIDATE_STORAGE_DISK` variables in the `.env` file to the name of the respective storage disk. Do not use the same disk for both! The content of the storage disks should be publicly accessible. Example for local disks:
    ```php
    // MAIA_TRAINING_PROPOSAL_STORAGE_DISK
    'maia-tp' => [
        'driver' => 'local',
        'root' => storage_path('app/public/maia-tp-patches'),
        'url' => env('APP_URL').'/storage/maia-tp-patches',
        'visibility' => 'public',
    ],
    // MAIA_ANNOTATION_CANDIDATE_STORAGE_DISK
    'maia-ac' => [
        'driver' => 'local',
        'root' => storage_path('app/public/maia-ac-patches'),
        'url' => env('APP_URL').'/storage/maia-ac-patches',
        'visibility' => 'public',
    ],
    ```
    This requires the link `storage -> ../storage/app/public` in the `public` directory.
5. Configure the storage disk for trained Mask R-CNN models. Set the `MAIA_MODEL_STORAGE_DISK` variable in the `.env` file to the name of the storage disk.

### If you use biigle/gpus on a remote machine

Add the following repositories to your `composer.json`:

```bash
composer config repositories.label-trees vcs https://github.com/biigle/label-trees
composer config repositories.projects vcs https://github.com/biigle/projects
composer config repositories.volumes vcs https://github.com/biigle/volumes
composer config repositories.largo vcs https://github.com/biigle/largo
composer config repositories.annotations vcs https://github.com/biigle/annotations
```

Add `$app->register(Biigle\Modules\Maia\MaiaGpuServiceProvider::class);` to `bootstrap/app.php`.

Configure the storage disk for trained Mask R-CNN models. Set the `MAIA_MODEL_STORAGE_DISK` variable in the `.env` file to the name of the storage disk. This must be the same storage disk than for your BIIGLE application instance.

## Configuration

New processing jobs are submitted to the `default` queue of the `gpu` connection. You can configure these with the `MAIA_REQUEST_QUEUE` and `MAIA_REQUEST_CONNECTION` environment variables.

The results of the processing jobs are submitted to the `default` queue of the `gpu-response` connection. You can configure these with the `MAIA_RESPONSE_QUEUE` and `MAIA_RESPONSE_CONNECTION` environment variables.
