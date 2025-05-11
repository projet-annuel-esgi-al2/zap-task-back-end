<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = Auth::user();
        $user->google_token = $googleUser->token;
        $user->google_refresh_token = $googleUser->refreshToken;
        $user->save();

        return response()->json(['message' => 'Compte Google connecté avec succès']);
    }
}