<?php

namespace App\Helpers;

use App\Events\UptimeCheckProcessed;
use App\Jobs\notifyUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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


    public function initCheck()
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
            // this is the case where the request has returned a response [either a success code or a failure code]

            // dispatch check Event
                // broadcast the check event ; then insert the values in the db
            UptimeCheckProcessed::dispatch($response);
        } catch (Exception $error) {
            return throw new Exception($error->getMessage());
        }
    }

    public function treatError(string $curlError)
    {
        // what are the different Failures to Consider if a request Failed (curl)
        // for now i only have the usecase of curlNum 6 & 60


        $this->dispatchNotifyUser($curlError); // enqueue a new (notification Job) & dispatch incident event
    }

    private function dispatchNotifyUser(string $curlErr)
    {
        //this should be agnostic to whatever thing it will do, either on a

        // check if the user has an Notification assigned to him, if he's notifiable or not
        // first a join from users & norification table
        $userNotification=(array)DB::table('notifications')->rightJoin('users','user_id','=','users.id')->get(['notifier_name','config','user_id','email'])[0];

        $notificationDetails=[
            'type'=>null,
            'value'=>null
        ];
        $curlErr=explode('(',$curlErr)[0]; // human readable message

        if($userNotification['notifier_name']==NULL){
            $notificationDetails['type']='mail';
            $notificationDetails['value']=$userNotification['email'];
        }else{
            $notificationDetails['type']='notification';
            $notificationDetails['value']=[
                'notifier'=>$userNotification['notifier_name'],
                'config'=>$userNotification['config']
            ];
        };
        notifyUser::dispatch($notificationDetails,$curlErr);

        // finally i'm pausing the monitor so the schedule won't pull it from db and do a check
        DB::table('monitors')->where('id','=',$this->monitor_id)->update(
            ['is_paused'=>true,
             'active'=>false]
        );

    }

    public static function trimCurlErrorNum(string $curlError)
    {
        // return the errorNUm
        $curlMessage = explode(":", $curlError)[0];
        preg_match('/\d{1,3}/', $curlMessage, $matches);
        return $matches[0];
    }
    // actions you need this to make
    // send emails/notification []
    // able to launch events []
    // fill the db with prepared DBQueries []
}
