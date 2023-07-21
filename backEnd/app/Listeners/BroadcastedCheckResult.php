<?php

namespace App\Listeners;

use App\Events\CheckedURL;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BroadcastedCheckResult
{
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
    public function handle(CheckedURL $event): void
    {
        //
    }
}
