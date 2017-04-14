<?php

Route::group(['prefix' => 'lbpushcenter', 'namespace' => 'libressltd\lbpushcenter\controllers', "middleware" => "Auth"], function (){

	Route::group(['middleware' => ['web']], function () {
		Route::resource("application", "Push_applicationController");
		Route::resource("application_type", "Push_applicationTypeController");
		Route::resource("device", "Push_deviceController");
		Route::resource("device.notification", "Push_deviceNotificationController");
		Route::resource("notification", "Push_notificationController");
		Route::resource("dashboard", "Push_dashboardController");
	});

	Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
		Route::resource("device", "Service\Push_deviceController");
		Route::post("device/{device_id}/clear_badge", "Service\Push_deviceController@postClearBadge");
	});

	Route::group(['prefix' => 'ajax', 'middleware' => ['web']], function () {
		Route::resource("device", "Ajax\Push_deviceController");
		Route::resource("worker", "Ajax\Push_workerController");
		Route::resource("application", "Ajax\Push_applicationController");
		Route::resource("notification", "Ajax\Push_notificationController");
	});
});