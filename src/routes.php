<?php

Route::group(['prefix' => 'lbpushcenter', 'namespace' => 'libressltd\lbpushcenter\controllers'], function (){

	Route::group(['middleware' => ['web', "auth"]], function () {
		Route::get("device/removeDuplicatedDevice", "Push_deviceController@removeDuplicatedDevice");
		Route::get("device/recoverOtherProblemDevice", "Push_deviceController@recoverOtherProblemDevice");
		Route::get("device/removeBadTokenDevice", "Push_deviceController@removeBadTokenDevice");

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

	Route::group(['prefix' => 'ajax', 'middleware' => ['web', "auth"]], function () {
		Route::resource("device", "Ajax\Push_deviceController");
		Route::resource("worker", "Ajax\Push_workerController");
		Route::resource("application", "Ajax\Push_applicationController");
		Route::resource("notification", "Ajax\Push_notificationController");
	});
});