<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushNotificationJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


	protect $device_token;
	protect $device_type;
	protect $message;
	
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($device_token, $device_type, $message)
    {
        $this->device_token = $device_token;
		$this->device_type = $device_type;
		$this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        PushNotification::app($this->device_type)->to($this->device_token)->send($this->message);
    }
}
