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
            __DIR__.'/migrations' => base_path('database/migrations'),
            __DIR__.'/models' => base_path('app/Models'),
            __DIR__.'/views' => base_path('resources/views/vendor'),
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
        $this->app->make('LIBRESSLtd\LBPushCenter\Controllers\Push_applicationController');
        $this->app->make('LIBRESSLtd\LBPushCenter\Controllers\Push_applicationTypeController');
        $this->app->make('LIBRESSLtd\LBPushCenter\Controllers\Push_deviceController');
        $this->app->make('LIBRESSLtd\LBPushCenter\Controllers\Push_userDeviceController');
    }
}
