## laravel-configs

Add the service provider to config/app.php in the providers array, or if you're using Laravel 5.5, this can be done via the automatic package discovery.

```php
LaravelConfigs\Providers\LaravelConfigsServiceProvider::class,
```

If you want you can use the facade. Add the reference in config/app.php to your aliases array.
```php
'SimpleConfig' => LaravelConfigs\Facades\SimpleConfig::class,
```

If you want you can get value in blade template.
```php
@simpleConfig('name','default')
```
