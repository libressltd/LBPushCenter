<?php

Route::group(['prefix' => 'services/pushcenter'], function () {
	Route::resource("push", "libressltd\lbpushcenter\controllers\PushController");
});
