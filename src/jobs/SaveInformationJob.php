<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use PushNotification;

class SaveInformationJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


	protected $information;
	
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($information)
    {
		$this->information = $this->information;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        foreach ($request->device_items as $information)
        {
            $device_token = $device["device_token"];
            $device_type = $device["device_type"];
            $message = $device["message"];

            dispatch(new PushNotificationJob($device_token, $device_type, $message));
        }
    }
}
