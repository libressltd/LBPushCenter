<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Push_device;

class PushNotificationJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


	protected $device_token;
	protected $title;
	protected $message;
	
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($device_token, $title, $message)
    {
        $this->device_token = $device_token;
		$this->title = $title;
		$this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $device = Push_device::findOrFail($this->device_token);
        $device->send($this->title, $this->message);
    }
}
