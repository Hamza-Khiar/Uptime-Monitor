<?php

namespace App\Jobs;

use App\Helpers\NotifiyerUtils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class notifyUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    private $notificationDetails;
    private string $error_mesg;
    public function __construct($notificationDetails,$error_mesg)
    {
        $this->notificationDetails=$notificationDetails;
        $this->error_mesg=$error_mesg;

        $this->onQueue('notifier');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // read the notificationDetails & check whether to send it via mail or via NotificationBroadcastChannel/as a Notification
       match($this->notificationDetails['type']){
            'notification'=>true, // send a notification,by an event and make the listener recieve it via a broadcasted channel
            'mail'=>NotifiyerUtils::viaEmail($this->notificationDetails['value'],$this->error_mesg) // send an email
        };
    }
}
