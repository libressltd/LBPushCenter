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
		$this->publishes([
	        __DIR__.'/jobs' => base_path('app/Jobs'),
	    ], 'lbpushcenter');
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
