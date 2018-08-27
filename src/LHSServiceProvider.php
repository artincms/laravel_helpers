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
    	// the main router
        $this->loadRoutesFrom( __DIR__.'/Routes/backend_lts_route.php');
        $this->loadRoutesFrom( __DIR__.'/Routes/frontend_lts_route.php');
	    // the main views folder
	    $this->loadViewsFrom(__DIR__ . '/Views', 'laravel_tagable');
	    // the main migration folder for create sms_ir tables

	    // for publish the views into main app
	    $this->publishes([
		    __DIR__ . '/Views' => resource_path('views/vendor/laravel_tagable'),
	    ]);

	    $this->publishes([
		    __DIR__ . '/Database/Migrations/' => database_path('migrations')
	    ], 'migrations');

	    // for publish the assets files into main app
	    $this->publishes([
		    __DIR__.'/assets' => public_path('vendor/laravel_tagable'),
	    ], 'public');

	    // for publish the sms_ir config file to the main app config folder
	    $this->publishes([
		    __DIR__ . '/Config/LTS.php' => config_path('laravel_tagable.php'),
	    ]);
        $this->publishes([
            __DIR__ . '/Traits/LaraveTagablesSystem.php' => app_path('Traits/LaravelCommentSystem.php'),
        ]);
        $this->publishes([
            __DIR__ . '/Components' => resource_path('assets/js/components/laravel_tagable'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    	// set the main config file
	    $this->mergeConfigFrom(
		    __DIR__ . '/Config/LTS.php', 'laravel_tagable'
	    );

		// bind the LTS Facade
	    $this->app->bind('LTS', function () {
		    return new LTS;
	    });
    }
}
