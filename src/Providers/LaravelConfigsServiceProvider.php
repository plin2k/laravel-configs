<?php

namespace LaravelConfigs\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use LaravelConfigs\Services\SimpleConfig;

class LaravelConfigsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('SimpleConfig', function () {
            return new SimpleConfig;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Blade::directive('simpleConfig', function ($name, $default = null) {
            return "<?php echo SimpleConfig::get($name, $default); ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['LaravelConfigs'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');
    }
}
