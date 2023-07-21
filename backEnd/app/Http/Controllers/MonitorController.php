<?php

namespace App\Http\Controllers;

use App\Jobs\CheckURL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function index()
    {
        $user = DB::table('users')->where('id', '=', Auth::id())->first(['email', 'username']);
        $user_monitors = DB::table('monitors')->where('user_id', '=', Auth::id())->get();
        return response()->json(['body' => [
            'User' => $user,
            'Monitors' => $user_monitors
        ]]);
    }
    public function newMonitor(Request $request)
    {
        // you'll add the monitor to the db and trigger a Controller Method for CRONJOBS to send requests to the provided url
        DB::table('monitors')->insert([
            'user_id' => Auth::id(),
            'friendly_name' => $request->friendlyName,
            'url' => $request->get('url'),
            'interval' => $request->interval,
            'tags' => $request->tags,
        ]);
        // when it's saved in the db trigger a cron job to send req
        // what kind of response should i send

        return response()->json([
            'Body'=>'Url will be monitored'
        ]);
    }
}

