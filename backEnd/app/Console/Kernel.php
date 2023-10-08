<?php

namespace App\Console;

use App\Jobs\CheckURL;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->call();
        // retrieve all the urls in monitor that the users entered; retrieve by join where user::id==Auth::id()

        $monitorData = DB::table('monitors')->where('is_paused','=',false)->get(['id','url','user_id','interval']);
        foreach($monitorData as $monitor){
            $schedule->job(new CheckURL($monitor->url,$monitor->id,$monitor->user_id))->cron("*/$monitor->interval * * * *");
        };
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
