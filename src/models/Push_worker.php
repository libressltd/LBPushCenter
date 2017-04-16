<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use Carbon\Carbon;
use App\Models\Push_notification;

class Push_worker extends Model
{
    use Uuid32ModelTrait;
    protected $appends = ["is_offline", "is_inactive"];

    public function start_work()
    {
        $notification = $this->notifications()->first();
    }

    public function findSame($notification)
    {
        if ($notification->device->application->type_id == 1)
        {
            // ios
        }
        else
        {
            
        }
    }

    public function notifications()
    {
        return $this->hasMany("App\Models\Push_notification", "worker_id");
    }

    public function isOffline()
    {
    	return $this->updated_at->addMinutes(1)->isPast();
    }

    public function isInactive()
    {
    	return $this->updated_at->addSeconds(30)->isPast();
    }

    public function getIsOfflineAttribute()
    {
        return $this->isOffline();
    }

    public function getIsInactiveAttribute()
    {
        return $this->isInactive();
    }

    public function clearNotification()
    {
    	Push_notification::where("worker_id", $this->id)->update(["worker_id" => null]);
    }
}
