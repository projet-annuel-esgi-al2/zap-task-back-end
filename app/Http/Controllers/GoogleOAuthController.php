<?php
namespace App\Http\Controllers;

use Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Service;
use App\Models\UserService;
use Illuminate\Support\Facades\Hash;

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
                'name'     => $googleUser->name,
                'password' => Hash::make('123456'),
            ]
        );

        Auth::login($user);

        $service = Service::where('name', 'google_calendar')->firstOrFail();

        UserService::updateOrCreate(
            [
                'user_id'    => $user->id,
                'service_id' => $service->id,
            ],
            [
                'id'               => \Illuminate\Support\Str::uuid(),
                'access_token'     => $googleUser->token,
                'refresh_token'    => $googleUser->refreshToken,
                'token_expires_at' => now()->addSeconds($googleUser->expiresIn),
            ]
        );

        return redirect('/');
    }

}
