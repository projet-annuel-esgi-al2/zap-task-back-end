<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleOAuthController extends Controller
{
    public function redirect()
    {
        $client = new Client;

        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/calendar'])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'select_account',
            ])
            ->redirect();
    }

    // Call back avec authentification laravel
    /**
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = Auth::user();

        $user->google_access_token = $googleUser->token;
        $user->google_refresh_token = $googleUser->refreshToken;
        $user->save();

        return redirect('/');
    }
     * */

    // callback debuggage
    public function callback()
    {
        /** @var \Laravel\Socialite\Two\User $googleUser */
        $googleUser = Socialite::driver('google')->stateless()->user();
        Log::info(json_encode($googleUser->attributes));
        Log::info($googleUser->token);
        Log::info($googleUser->refreshToken);
        Log::info($googleUser->expiresIn);

        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,

            ]
        );

        Auth::login($user);

        return redirect('/');
    }
}
