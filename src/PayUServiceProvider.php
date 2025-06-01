<?php

namespace PayU\LaravelPayU;

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
        $this->publishes([
            __DIR__ . '/../config/payu.php' => config_path('payu.php'),
        ], 'payu-config');
    }
}
