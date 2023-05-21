<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        /* 
            i'm checking on whether if the user is registered in the db and redirect to the dashboard
        */
    }
    public function register(Request $request)
    {
        /* 
            i'm getting a form as JSON and i should validate it & send an email to it; then saving it in the db and redirecting to the dashboard
        */

        $form_validation = $request->validate([
            'username' => ['required', 'min:8'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'min:8', 'same:password']
        ]);
        return response()->json([
            'Body' => 'It registered'
        ], 201, [
            'Accept' => 'application/json'
        ]);
    }
}
