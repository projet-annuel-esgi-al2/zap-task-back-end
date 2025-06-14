<?php
namespace App\Http\Controllers;

use Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Service;
use App\Models\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


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
        //dd($service->id);


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


        // DEBBUGGAGE A REFACTO
        $channelId = (string) Str::uuid();

        // 2. Créer le webhook avec l'API Google Calendar
        $response = Http::withToken($googleUser->token)->post('https://www.googleapis.com/calendar/v3/calendars/primary/events/watch', [
            'id'      => $channelId,
            'type'    => 'web_hook',
            'address' => url('/google/calendar/webhook'),

        ]);

        if (!$response->successful()) {
            \Log::error('Erreur création webhook Google Calendar', [
                'response' => $response->json(),
            ]);
            abort(500, 'Erreur lors de la création du webhook Google.');
        }

        $data = $response->json();

        // 3. Sauvegarder les infos dans la base (dans une table à créer, voir étape suivante)
        \DB::table('webhook_channels')->insert([
            'id'          => Str::uuid(),
            'user_id'     => $user->id,
            'service_id'  => $service->id,
            'channel_id'  => (string) Str::uuid(),         // ID donné par toi
            'resource_id' => $data['resourceId'], // ID donné par Google
            'calendar_id' => 'primary',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);




        return redirect('/');
    }

}
