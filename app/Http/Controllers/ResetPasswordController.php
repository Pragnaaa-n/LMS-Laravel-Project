<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Student;
use Str;
use Password;

use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|string|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function(Student $user, string $password){
                $user->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60)
                ])->save();

            }
        );
        return $status === Password::RESET_LINK_SENT ? response()->json([
            'message'=> __($status)

        ], 200) : response()->json([
            'message'=> __($status)
        ], 400);
    }
}
