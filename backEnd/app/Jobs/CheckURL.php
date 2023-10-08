<?php

namespace App\Jobs;

use App\Helpers\UptimeCheck;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;



class CheckURL implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected string $url;
    protected $monitor_id;
    private $response_to_broadcast = [];
    protected $user_id;

    /**
     * Create a new job instance.
     */

    public function __construct(string $url, $monitor_id,$user_id)
    {
        $this->url = $url;
        $this->monitor_id = $monitor_id;
        $this->user_id=$user_id;

        $this->onQueue('checks');
    }

    public function handle(): void
    {

        $uptime_check = new UptimeCheck($this->url, $this->monitor_id,$this->user_id);
        try {
            $result = $uptime_check->initCheck();
            $this->response_to_broadcast=$result;
            // if the returned data was $respone_data[]
        } catch (Exception $error) {
            $uptime_check->treatError($error->getMessage());
        }finally{
            // this is will run on both success & failure
        }

    }
}

           //  DB::table('checks')->insert([
           //     'check_uuid'=>Str::uuid(),
           //     'monitor_id'=>$this->id,
           //     'status_cod ffe'=>$response_to_broadcast['status_code'],
           //     'timestamp'=>$carboned_time,
           //     'response_time'=>$response_to_broadcast['response_time'],
           //     'ssl_certificate'=>false,
           //     'created_at'=>Carbon::now(),
           //     'updated_at'=>Carbon::now()
           // ]);
           // if($response->failed()){
           //     // think about how you'll deal with incidents
           //     // basically this
           //         /*
           //         * i'm making requests to the urls that the user gives me,it's gonna check anyway hence the first insert in DB
           //         * but if it's an incident, i have to firstly insert the values of 'first_timestamp','monitor_id' & 'status_code'
           //         * then if the next consequent requests fails, i update the lastest_timestamp ²
           //         */
           //     IncidentStateEvent::dispatch('UP',[$this->id,$response_to_broadcast['status_code'],$carboned_time]);
           // }
