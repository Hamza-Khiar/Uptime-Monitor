<?php

namespace App\Helpers;

use App\Helpers\Types\DBTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Spatie\SslCertificate\SslCertificate;

class UptimeCheck
{

    // why i'm making this helper class
    //      to help me with the thick logic of the job, mainly what i'm doing is, make a request, raise event (incident & check) push to db
    //
    private const _UNRESOLVED_DNS_CURL_ERRNUM=6;

    protected string $url;
    protected string $monitor_id;
    private string $user_id;

    public function __construct(string $url,$monitor_id,$user_id)
    {
        $this->url = $url;
        $this->monitor_id = $monitor_id;
        $this->user_id = $user_id;
    }

    //first method: initCheck

    public function initCheck(): array|Exception
    {
        try {
            $start = microtime(true);
            $response = Http::get($this->url);
            $response_time = microtime(true) - $start;
            $carboned_time = Carbon::now();
            $response_data = [
                'monitor_id' => $this->monitor_id,
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
            UptimeCheck::_UNRESOLVED_DNS_CURL_ERRNUM => true, // enqueue a new (notification Job) & dispatch incident event
        };
    }

    private function trimCurlErrorNum(string $curlError)
    {
        // return the errorNUm
        $curlMessage = explode(":", $curlError)[0];
        preg_match('/\d{1,3}/', $curlMessage, $matches);
        return $matches[0];
    }

    private function dispatchNotificationJob()
    {
        // this will dispatch the notification process, either via Email or provided platform; depends on which the user has set up, if none; use email to notify
    }

    private function raiseIncidentEvent()
    {
        // this will raise the incident event & return this for method chaining
    }

    private function raiseCheckEvent()
    {
        // this will raise the check event & return this for method chaining
    }


    // actions you need this to make
    // send emails/notification []
    // able to launch events []
    // fill the db with prepared DBQueries []
}
