<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('stripe', function ($app) {
            $config = $app['config']['services.stripe'];
            Stripe::setApiKey($config['secret']);
            return new \Stripe\StripeClient($config['secret']);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        Stripe::setAppInfo(
            "Nafsaji Payment",
            "1.0.0",
            "https://nafsaji.com"
        );
    }
}
