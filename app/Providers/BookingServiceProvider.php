<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BookingStripeService;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BookingStripeService::class, function ($app) {
            return new BookingStripeService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
