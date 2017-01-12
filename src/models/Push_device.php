<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use App\Models\Push_application;

class Push_device extends Model
{
    use Uuid32ModelTrait;

    static function add($token, $app_name)
    {
    	$app = Push_application::where("name", $app_name)->firstOrFail();
    	$device = new Push_device;
    	$device->device_token = $token;
    	$device->app_id = $app->id;
    	$device->save();

    	return $device;
    }
}
