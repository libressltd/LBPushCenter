<?php

namespace LIBRESSLtd\LBPushCenter;

use Illuminate\Support\ServiceProvider;

class LBPushCenterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views/role', 'role');
        $this->loadViewsFrom(__DIR__.'/views', 'dp');
		$this->publishes([
	        __DIR__.'/jobs' => base_path('app/Jobs'),
	    ], 'deeppermission');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('LIBRESSLtd\LBPushCenter\Controllers\PushController');
    }
}
