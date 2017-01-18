<?php


Route::resource("lbpushcenter/application", "libressltd\lbpushcenter\controllers\Push_applicationController");
Route::resource("lbpushcenter/application_type", "libressltd\lbpushcenter\controllers\Push_applicationTypeController");
Route::resource("lbpushcenter/device", "libressltd\lbpushcenter\controllers\Push_deviceController");
Route::resource("lbpushcenter/device.notification", "libressltd\lbpushcenter\controllers\Push_deviceNotificationController");
Route::resource("lbpushcenter/notification", "libressltd\lbpushcenter\controllers\Push_notificationController");

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
	Route::resource("lbpushcenter/device", "libressltd\lbpushcenter\controllers\Service\Push_deviceController");
	Route::post("lbpushcenter/device/{device_id}/clear_badge", "libressltd\lbpushcenter\controllers\Service\Push_deviceController@postClearBadge");
});