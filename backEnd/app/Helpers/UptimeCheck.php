<?php

namespace App\Helpers;

use App\Helpers\Types\CurlCases;
use App\Jobs\notifyUser;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Spatie\SslCertificate\SslCertificate;

class UptimeCheck
{

    // why i'm making this helper class
    //      to help me with the thick logic of the job, mainly what i'm doing is, make a request, raise event (incident & check) push to db
    //

    protected string $url;
    protected string $monitor_id;
    private string $user_id;

    public function __construct(string $url,$monitor_id,$user_id)
    {
        $this->url = $url;
        $this->monitor_id = $monitor_id;
        $this->user_id =$user_id;
    }


    public function initCheck(): array|Exception
    {
        try {
            $start = microtime(true);
            $response = Http::get($this->url);
            $response_time = microtime(true) - $start;
            $carboned_time = Carbon::now();
            $response_data = [
                'monitor_id' => $this->user_id,
                'status_code' => $response->status(),
                'response_time' => $response_time,
                'at' => $carboned_time,
                'ssl_certificate' => true //$response['ssl_chiHaja']
            ];
            var_dump($response);
            return $response_data;
        } catch (Exception $error) {
            return throw new Exception($error->getMessage());
        }
    }

    public function treatError(string $curlError)
    {
        $curlNumError = $this->trimCurlErrorNum($curlError);
        // make an enum
        match ($curlNumError) {
            CurlCases::UNRESOLVED_DNS_CURLERR->value => $this->dispatchNotifyUser($curlError) // enqueue a new (notification Job) & dispatch incident event
        };
    }

    private function trimCurlErrorNum(string $curlError)
    {
        // return the errorNUm
        $curlMessage = explode(":", $curlError)[0];
        preg_match('/\d{1,3}/', $curlMessage, $matches);
        return $matches[0];
    }

    private function dispatchNotifyUser(?string $curlErr):UptimeCheck
    {
        // check if the user has an Notification assigned to him, if he's notifiable or not
        $user=User::where('id','=',$this->user_id)->first();
        $curlErr=explode('(',$curlErr)[0]; // human readable message
        var_dump($curlErr);
        notifyUser::dispatch($user,$curlErr);
        return $this;
    }

    // actions you need this to make
    // send emails/notification []
    // able to launch events []
    // fill the db with prepared DBQueries []
}
