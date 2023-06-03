<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * ********************************
     * main Controller actions/methods
     * ********************************
     */
    public function login(Request $request)
    {
        /**
         * first step : validate form & check if there is a user with the same credentials;
         * second step : if there is 
         */
        $form_validation = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        if (Auth::guard('web')->attempt($form_validation)) {
            dd($request->session()->invalidate());
        }
        return response('hey there login');
    }
    public function register(Request $request)
    {
        $form_validation = $request->validate([
            'username' => ['required', 'min:8', 'max:50'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'min:8', 'same:password']
        ]);

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
    private function tokenIssuer()
    {
        // 
    }
}
