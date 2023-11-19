<?php

namespace App\Providers;

use App\Providers\UptimeCheckProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TreatCheckResponse
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
    public function handle(UptimeCheckProcessed $event): void
    {
        //
    }
}
