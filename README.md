# BIIGLE MAIA Module

Install the module:

```
composer config repositories.laravel-remote-queue vcs https://github.com/biigle/maia
composer require biigle/maia
```

1. Add `'Biigle\Modules\Maia\MaiaServiceProvider'` to the `providers` array in `config/app.php`.
2. Run `php artisan maia:publish` to refresh the public assets of this package. Do this for every update of the package.
