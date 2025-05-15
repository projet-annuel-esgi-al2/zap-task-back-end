<?php
namespace App\Http\Controllers;

use Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleOAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/calendar'])
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


    //callback debuggage
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();


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
