<?php

namespace App\Http\Controllers;
use Password;

use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ? response()->json([
            'message'=>__($status)
        ],200) : response()->json([
            'message'=>__($status)
        ],400);
    }
}
