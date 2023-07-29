<?php

namespace App\Jobs;

use App\Events\IncidentStateEvent;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\SslCertificate\SslCertificate;
use Throwable;

class CheckURL implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected string $url;
    protected $id;
    /**
     * Create a new job instance.
     */

    public function __construct(string $url,$id)
    {
        $this->url=$url;
        $this->id=$id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // what needs to be done?
        // the job will basically execute a request to the provided URL, the value that's returned will be either an incident or non-incident check
        // the response time will be recorded with microtimes

        $start=microtime(true);
        $response=Http::get($this->url);
        $response_time=microtime(true)-$start;
        $carboned_time=Carbon::now();// to eliminate milliseconds offset for timestamps of a request

        info($response->status());

        $response_to_broadcast=[
            'monitor_id'=>$this->id,
            'status_code'=>$response->status(),
            'response_time'=>$response_time,
            'at'=>$carboned_time,
            'ssl_certificate'=>SslCertificate::createForHostName($this->url)->isValid()
        ];

        // you send the $response_to_broadcast array as an event
        DB::table('checks')->insert([
            'check_uuid'=>Str::uuid(),
            'monitor_id'=>$this->id,
            'status_code'=>$response_to_broadcast['status_code'],
            'timestamp'=>$carboned_time,
            'response_time'=>$response_to_broadcast['response_time'],
            'ssl_certificate'=>$response_to_broadcast['ssl_certificate'],
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
        // if the code is 4/5XX
        if($response->failed()){
            // think about how you'll deal with incidents
            // basically this
                /*
                * i'm making requests to the urls that the user gives me,it's gonna check anyway hence the first insert in DB
                * but if it's an incident, i have to firstly insert the values of 'first_timestamp','monitor_id' & 'status_code'
                * then if the next consequent requests fails, i update the lastest_timestamp ²
                */
            IncidentStateEvent::dispatch('UP',[$this->id,$response_to_broadcast['status_code'],$carboned_time]);
        }
    }
}
