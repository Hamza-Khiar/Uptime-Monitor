<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function index()
    {
        $user = DB::table('users')->where('id', '=', Auth::id())->get(['email', 'username']);
        $user_monitors = DB::table('monitors')->where('user_id', '=', Auth::id())->get();
        return response()->json(['body' => [
            'User' => $user,
            'Monitors' => $user_monitors
        ]]);
    }
}
