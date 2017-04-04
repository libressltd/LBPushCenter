<?php

namespace App\Console\Commands;
use App\Models\Push_notification;

use Illuminate\Console\Command;

class LBPushCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lbpushcenter:push';

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
        while (1)
        {
            $notification = Push_notification::whereStatusId(1)->orderBy("created_at", "desc")->first();
            if ($notification)
            {
                $notification->send();
            }
            sleep(1);
        }
    }
}
