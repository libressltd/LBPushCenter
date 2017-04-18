<?php

namespace App\Console\Commands;
use App\Models\Push_notification;

use Illuminate\Console\Command;
use App\Models\Push_worker;
use Carbon\Carbon;

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
        $per_time = 200;
        $max_group = 1000;
        $mode = $this->option('mode');
        if ($mode === "all")
        {
            while (1)
            {
                $notifications = Push_notification::with("device", "device.application")->take($per_time)->get();
                if ($notifications->count() > 0)
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
                if ($worker->notifications()->count() > 0)
                {
                    $worker->start_work();
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
                Push_worker::where("updated_at", "<", Carbon::now()->addMinutes(-5))->delete();

                if (Push_notification::whereNull("worker_id")->count() > 0)
                {
                    $workers = Push_worker::withCount("notifications")->get();
                    if ($workers->count() > 0)
                    {
                        foreach ($workers as $worker)
                        {
                            if ($worker->notifications_count < $max_group)
                            {
                                Push_notification::whereNull("worker_id")->take($per_time)->update(["worker_id" => $worker->id]);
                            }
                        }
                    }
                    else
                    {
                        sleep(1);
                    }
                }
                else
                {
                    sleep(1);
                }
            }
        }
    }
}
