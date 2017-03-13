<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use App\Models\Push_application;
use App\Models\Push_notification;
use GuzzleHttp\Client;
use App\Jobs\PushNotificationJob;
use LIBRESSLtd\LBForm\Traits\LBDatatableTrait;
use Form;

class Push_device extends Model
{
    use Uuid32ModelTrait, LBDatatableTrait;

    protected $appends = ["badge", "users_string", "notification_button"];

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
        if (!$this->enabled) return;
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
        if (!$this->enabled) return;
        $notification = new Push_notification;
        $notification->device_id = $this->id;
        $notification->title = $title;
        $notification->message = $desc;
        $notification->status_id = 1;
        $notification->save();

        $notification->send();
    }
    
    public function getNotificationButtonAttribute()
    {
        return Form::lbButton("lbpushcenter/device/$this->id/notification/create", "GET", trans('lbpushcenter.device.notification.title'), ["class" => "btn btn-primary btn-xs"])->toHtml();
    }

    public function getBadgeAttribute()
    {
        return $this->badge();
    }

    public function getUsersStringAttribute()
    {
        $users = [];
        foreach ($this->users as $user)
        {
            $users[] = $user->name;
        }
        return implode(", ", $users);
    }

    public function badge()
    {
        return $this->notifications()->sent()->count();
    }

    public function clear_badge()
    {
        $this->notifications()->sent()->update(["status_id" => 4]);
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
