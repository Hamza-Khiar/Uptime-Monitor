<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;

class UserController extends Controller
{
    /**
     * ********************************
     * main Controller actions/methods
     * ********************************
     */


    public function login(LoginRequest $request)
    {
        /**
         * first step : validate form & check if there is a user with the same credentials;
         * second step : if there is attempt the auth, if
         */
        $form_validation = $request->validated();
        // check if there is a user with already in the db to make an attempt
        $user = DB::table('users')->where('email', '=', $form_validation['email'])->get(['email', 'password','verified_at']);
        if ($user->isEmpty()) {
            return response()->json([
                'Error' => 'the provided credentials doesn\'t exist in our records, please register '
            ]);
        }
        if ($user->value('email') !== $form_validation['email'] && !Hash::check($form_validation['password'], $user->value('password'))) {
            return response()->json([
                'Error' => 'the provided credentials are mismatched with our record; please check the email or password'
            ]);
        }
        if (!$user->value('verified_at')) {
            return response()->json(['Error' => 'this user isn\'t validated yet, please check your email']);
        }

        if (Auth::attempt($form_validation)) {
            $request->session()->regenerate();
            return response()->json([
                'Body' => 'Registered'
            ]);
        }
    }
    public function register(RegistrationRequest $request)
    {
        $form_validation = $request->validated();
        $check_user = DB::table('users')->where('email', '=', $form_validation['email'])->orWhere('username', '=', $form_validation['username'])->get();

        if ($check_user->isEmpty()) {
            $id = DB::table('users')->insertGetId([
                'username' => $form_validation['username'],
                'email' => $form_validation['email'],
                'password' => bcrypt($form_validation['password']),
                'verified_at' => null
            ], 'id');
            $this->emailVerification($form_validation['email'], $id);
            return response()->json([
                'Verification' => 'we just sent an email, please verify'
            ]);
        } else {
            $errors = $this->checkSimilarData($check_user, $form_validation);
            return response()->json([
                'Error' => $errors
            ], 500);
        }
    }
    public function validateUser(int $id)
    {

        if (DB::table('users')->where('id', '=', "$id")->get('verified_at')->value('verified_at') == null) {
            DB::table('users')->where('id', '=', "$id")->update([
                'verified_at' => Carbon::now()
            ]);
        }
        return redirect(env('FRONTEND_URL') . '/login');
        /*
            find a way to redirect in front-end with appropriate data:
                - user info :user_name & email
                - monitors
        }
        */
    }

    /**
     * *********************************
     * private functions
     * *********************************
     */
    private function checkSimilarData(Collection $check_user, array $form_validation)
    {
        $errors = [];
        $elments_to_check = ['username', 'email'];
        foreach ($elments_to_check as $value) {
            if ($form_validation[$value] == $check_user->value($value)) {
                $errors[$value] = [
                    "message" => "there is a user with the same $value as you provided : $value = $form_validation[$value]"
                ];
            };
        }
        return $errors;
    }
    private function emailVerification(string $email, int $id)
    {
        Mail::to($email)->send(new EmailVerification($id));
    }
}
