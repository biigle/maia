# BIIGLE MAIA Module

Install the module:

```
composer config repositories.maia vcs https://github.com/biigle/maia
composer require biigle/maia
```

1. Add `'Biigle\Modules\Maia\MaiaServiceProvider'` to the `providers` array in `config/app.php`.
2. Run `php artisan maia:publish` to refresh the public assets of this package. Do this for every update of the package.

Configuration:

New processing jobs are submitted to the `default` queue of the `gpu` connection. You can configure these with the `MAIA_REQUEST_QUEUE` and `MAIA_REQUEST_CONNECTION` environment variables.

The results of the processing jobs are submitted to the `default` queue of the `biigle` connection. You can configure these with the `MAIA_RESPONSE_QUEUE` and `MAIA_RESPONSE_CONNECTION` environment variables.
