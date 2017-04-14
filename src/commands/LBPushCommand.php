<?php

namespace App\Console\Commands;
use App\Models\Push_notification;

use Illuminate\Console\Command;
use App\Models\Push_worker;

class LBPushCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lbpushcenter:push {--mode=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push notification in queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mode = $this->option('mode');
        if ($mode === "all")
        {
            while (1)
            {
                $notifications = Push_notification::with("device", "device.application")->take(20)->get();
                if ($notifications)
                {
                    foreach ($notifications as $notification)
                    {
                        $notification->send();
                    }
                }
                else
                {
                    sleep(1);
                }
            }
        }
        else if ($mode === "worker")
        {
            $worker = new Push_worker;
            $worker->save();
            while (1)
            {
                $worker->touch();
                $notifications = Push_notification::where("worker_id", $worker->id)->take(20)->with("device", "device.application")->get();
                if ($notifications)
                {
                    foreach ($notifications as $notification)
                    {
                        $notification->send();
                    }
                }
                else
                {
                    sleep(1);
                }
            }
        }
        else if ($mode === "master")
        {
            while (1)
            {
                $workers = Push_worker::withCount("notifications")->get();
                foreach ($workers as $worker)
                {
                    if ($worker->isOffline())
                    {
                        $worker->clearNotification();
                        $worker->delete();
                        continue;
                    }
                    if ($worker->notifications_count < 100)
                    {
                        Push_notification::take(200)->update(["worker_id"], $worker->id);
                    }
                }
                sleep(1);
            }
        }
    }
}
