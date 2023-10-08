<?php

namespace App\Jobs;

use App\Notifications\URLMismatchIncident;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class notifyUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private Collection $user;
    private string $error_mesg;
    public function __construct($user,$error_mesg)
    {
        $this->user=$user;
        $this->error_mesg=$error_mesg;

        $this->onQueue('notifier');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Notification::send($this->user,new URLMismatchIncident($this->error_mesg));
        //
    }
}
