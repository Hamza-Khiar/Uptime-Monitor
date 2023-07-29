<?php

namespace App\Listeners;

use App\Events\IncidentStateEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class IncidentStateSyncher
{
    protected ?Carbon $first_timestamp = null;
    protected ?Carbon $latest_timestamp = null;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     */
    public function handle(IncidentStateEvent $event): void
    {
        // it will always check if the event is up or down & do stuff accordingly.

        if ($event->incident_status === 'UP' && $this->first_timestamp == null) {
            $this->first_timestamp = $event->incident_info[2];
            DB::table('incidents')->insert([
                'monitor_id' => $event->incident_info[0],
                'status_code' => $event->incident_info[1],
                'first_timestamp' => $this->first_timestamp
            ]);

            //trigger a notification to send it to whatever the user has enabled.

        } elseif ($event->incident_status == 'UP' && $this->first_timestamp != null) {
            $this->latest_timestamp = $event->incident_info[2];
            DB::table('incidents')->updateOrInsert(
                [
                    'monitor_id' => $event->incident_info[0],
                    'first_timestamp' => $this->first_timestamp
                ],
                ['latest_timestamp' => $this->latest_timestamp]
            );
        }elseif($event->incident_status=='DOWN'){
            $duration=$this->first_timestamp->diffInMinutes($this->latest_timestamp);
            DB::table('incidents')->upsert(
            [
                [
                    'monitor_id'=>$event->incident_info[0],
                    'first_timestamp'=>$this->first_timestamp,
                    'latest_timestamp'=>$this->latest_timestamp,
                    'duration'=>$duration
                ]
            ],
            ['monitor_id','first_timestamp'],
            ['latest_timestamp','duration']
            );

            $this->latest_timestamp=null;
            $this->first_timestamp=null;
        }
    }
}
