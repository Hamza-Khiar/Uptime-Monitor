<?php

namespace App\Helpers;

use App\Mail\FailedRequest;
use Illuminate\Support\Facades\Mail;

class NotifiyerUtils {

    // this class will be responsible on notifiying the user based on what's available to him (if he provided a Notifier or should be sent on email, that's determined on the job tho)
    // it will be triggered from the notifyUser Job


    public static function viaEmail(string $email,string $message){
        // for now this will compose the email & send the email to user
            Mail::to($email)->send(new FailedRequest($message));
    }

    public static function viaNotification(array $notifierDetails,string $message){
        // this will take the notifier details and connect to them then broadcast to the user's provided Notifier on the issue/message

    }
}
