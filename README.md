# BIIGLE MAIA Module

[![Test status](https://github.com/biigle/maia/workflows/Tests/badge.svg)](https://github.com/biigle/maia/actions?query=workflow%3ATests)

This is the BIIGLE module module for the [Machine Learning Assisted Image Annotation](https://doi.org/10.1371/journal.pone.0207498) method.

## Installation

1. Run `composer require biigle/maia`.
2. Install the Python dependencies with `pip install -r requirements.txt`.
3. Add `Biigle\Modules\Maia\MaiaServiceProvider::class` to the `providers` array in `config/app.php`.
4. Run `php artisan vendor:publish --tag=public` to publish the public assets of this module.
5. Run `docker-compose exec app php artisan migrate` to create the new database tables.
6. Configure a storage disk each for the training proposal and annotation candidate patches. Set the `MAIA_TRAINING_PROPOSAL_STORAGE_DISK` and `MAIA_ANNOTATION_CANDIDATE_STORAGE_DISK` variables in the `.env` file to the name of the respective storage disk. Do not use the same disk for both! The content of the storage disks should be publicly accessible. Example for local disks:
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

## Configuration

New processing jobs are submitted to the `default` queue of the `gpu` connection. You can configure these with the `MAIA_JOB_QUEUE` and `MAIA_JOB_CONNECTION` environment variables.

## References

Reference publications that you should cite if you use MAIA for one of your studies.

- **BIIGLE 2.0**
    [Langenkämper, D., Zurowietz, M., Schoening, T., & Nattkemper, T. W. (2017). Biigle 2.0-browsing and annotating large marine image collections.](https://doi.org/10.3389/fmars.2017.00083)
    Frontiers in Marine Science, 4, 83. doi: `10.3389/fmars.2017.00083`

- **MAIA**
    [Zurowietz, M., Langenkämper, D., Hosking, B., Ruhl, H. A., & Nattkemper, T. W. (2018). MAIA—A machine learning assisted image annotation method for environmental monitoring and exploration.](https://doi.org/10.1371/journal.pone.0207498)
    PloS one, 13(11), e0207498. doi: `10.1371/journal.pone.0207498`

## Developing

Take a look at the [development guide](https://github.com/biigle/core/blob/master/DEVELOPING.md) of the core repository to get started with the development setup.

Want to develop a new module? Head over to the [biigle/module](https://github.com/biigle/module) template repository.

## Contributions and bug reports

Contributions to BIIGLE are always welcome. Check out the [contribution guide](https://github.com/biigle/core/blob/master/CONTRIBUTING.md) to get started.
