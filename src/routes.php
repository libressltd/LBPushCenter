<?php

Route::group(['middleware' => 'web', 'prefix' => 'services/pushcenter'], function () {
	Route::resource("push", "libressltd\lbpushcenter\controllers\PushController");
});
