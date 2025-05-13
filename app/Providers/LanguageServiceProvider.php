<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setLocaleFromSession();
    }

    /**
     * Set the application locale based on session.
     */
    protected function setLocaleFromSession()
    {
        // Only run this after the application is fully booted
        $this->app->booted(function () {
            try {
                // Check if session has started and has a locale
                if (Session::has('locale')) {
                    $locale = Session::get('locale');
                    
                    // Only set if it's a valid locale
                    if (in_array($locale, ['ar', 'en'])) {
                        // Set locale for the application
                        App::setLocale($locale);
                        Log::info('LanguageServiceProvider set locale to: ' . $locale);
                    }
                } elseif (request()->hasCookie('locale')) {
                    // Fallback to cookie if session doesn't have locale
                    $locale = request()->cookie('locale');
                    
                    if (in_array($locale, ['ar', 'en'])) {
                        App::setLocale($locale);
                        // Also save to session for future requests
                        Session::put('locale', $locale);
                        Log::info('LanguageServiceProvider set locale from cookie to: ' . $locale);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error in LanguageServiceProvider: ' . $e->getMessage());
            }
        });
    }
}
