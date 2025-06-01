<?php

namespace SarfarazStark\LaravelPayU;

use Illuminate\Support\ServiceProvider;

class PayUServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        $this->app->singleton('payu', function ($app) {
            return new PayU();
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/payu.php',
            'payu'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/payu.php' => config_path('payu.php'),
        ], 'payu-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'payu-migrations');

        // Load migrations when testing
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}
