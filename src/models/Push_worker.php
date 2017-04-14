<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use Carbon\Carbon;
use App\Models\Push_notification;

class Push_worker extends Model
{
    use Uuid32ModelTrait;

    public function notifications()
    {
        return $this->hasMany("App\Models\Push_notification", "worker_id");
    }

    public function isOffline()
    {
    	return $this->updated_at->addMinutes(5)->isPast();
    }

    public function isActive()
    {
    	return $this->updated_at->addMinutes(1)->isPast();
    }

    public function clearNotification()
    {
    	Push_notification::where("worker_id", $this->id)->update(["worker_id" => null]);
    }
}
