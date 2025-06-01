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

        // Publish models
        $this->publishes([
            __DIR__ . '/Models/PublishablePayUTransaction.php' => app_path('Models/PayUTransaction.php'),
            __DIR__ . '/Models/PublishablePayURefund.php' => app_path('Models/PayURefund.php'),
            __DIR__ . '/Models/PublishablePayUWebhook.php' => app_path('Models/PayUWebhook.php'),
        ], 'payu-models');

        // Load migrations when testing
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}
