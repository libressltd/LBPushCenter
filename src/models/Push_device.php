<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use App\Models\Push_application;
use App\Models\Push_notification;
use GuzzleHttp\Client;
use App\Jobs\PushNotificationJob;

class Push_device extends Model
{
    use Uuid32ModelTrait;

    static function add($token, $app_name)
    {
        $app = Push_application::where("name", $app_name)->firstOrFail();
        $device = Push_device::where("device_token", $token)->where("application_id", $app->id)->first();
        if (!$device)
        {
            $device = new Push_device;
        }
        $device->device_token = $token;
        $device->application_id = $app->id;
        $device->save();

        return $device;
    }

    public function sendInQueue($title, $desc)
    {
        $notification = new Push_notification;
        $notification->device_id = $this->id;
        $notification->title = $title;
        $notification->message = $desc;
        $notification->status_id = 1;
        $notification->save();

        $job = (new PushNotificationJob($notification->id));
        dispatch($job);
    }

    public function send($title, $desc)
    {
        $notification = new Push_notification;
        $notification->device_id = $this->id;
        $notification->title = $title;
        $notification->message = $desc;
        $notification->status_id = 1;
        $notification->save();

        $notification->send();
    }

    public function number_of_unread()
    {
        return $this->notifications()->where("status_id", 2)->count();
    }

    // relationship

    public function notifications()
    {
        return $this->hasMany("App\Models\Push_notification", "device_id");
    }

    public function application()
    {
        return $this->belongsTo("App\Models\Push_application", "application_id");
    }

    public function users()
    {
        return $this->belongsToMany("App\Models\User", "push_user_devices", "device_id", "user_id");
    }
}
