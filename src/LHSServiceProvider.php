<?php

namespace ArtinCMS\LHS;

use Illuminate\Support\ServiceProvider;

class LHSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {
	    $this->publishes([
		    __DIR__.'/assets' => public_path('vendor/laravel_helpers'),
	    ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
	    $this->app->bind('LHS', function () {
		    return new LHS;
	    });
    }
}
