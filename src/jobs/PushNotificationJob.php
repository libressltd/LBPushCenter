<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Push_device;
use App\Models\Push_notification;

class PushNotificationJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $notification_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification_id)
    {
        $this->notification_id = $notification_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notificaiton = Push_notification::findOrFail($this->notification_id);
        $notificaiton->send();
    }
}
