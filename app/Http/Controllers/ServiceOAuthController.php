<?php

namespace App\Http\Controllers;

use App\Enums\Service\Identifier;
use App\Models\OAuthToken;
use App\Models\Service;
use App\Models\ServiceSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

class ServiceOAuthController extends Controller
{
    /**
     * Check if the authenticated user is subscribed to a service
     *
     * @authenticated
     *
     * @response 301 { "message": "User is not subscribed to this service."}
     *
     * @responseHeader Location string The redirect url
     *
     * @response 302
     */
    public function get(Identifier $serviceIdentifier): JsonResponse
    {
        if (! auth()->user()?->subscribedToService($serviceIdentifier)) {
            return response()
                ->json('User is not subscribed to this service', Response::HTTP_NOT_FOUND)
                ->withHeaders(['Location' => route('service-oauth-redirect', ['serviceIdentifier' => $serviceIdentifier])]);
        }

        return response()->json(null, Response::HTTP_FOUND);
    }

    public function redirect(Identifier $serviceIdentifier): RedirectResponse
    {
        $service = Service::firstWhere('identifier', $serviceIdentifier->value);

        $socialiteDriverIdentifier = $service->socialite_driver_identifier;
        $scopes = $service->scopes()->pluck('scope_value');

        /** @phpstan-ignore-next-line */
        $socialite = Socialite::driver($socialiteDriverIdentifier)
            ->scopes($scopes)
            ->stateless();

        if (! empty($service->oauth_token_options)) {
            $socialite->with($service->oauth_token_options);
        }

        return $socialite->redirectUrl('https://example.com')->redirect();
    }

    public function callback(Identifier $serviceIdentifier): RedirectResponse
    {
        $service = Service::where('identifier', $serviceIdentifier->value)->first();
        $oauthUser = Socialite::driver($service->socialite_driver_identifier)->user();

        $oauthToken = OAuthToken::create([
            'user_id' => auth()->user()->id,
            'value' => $oauthUser->token, // @phpstan-ignore-line
            'refresh_token' => $oauthUser->refreshToken, // @phpstan-ignore-line
            'expires_at' => now()->addSeconds($oauthUser->expiresIn), // @phpstan-ignore-line
        ]);

        ServiceSubscription::create([
            'service_id' => $service->id,
            'oauth_token_id' => $oauthToken->id,
        ]);

        return redirect('/');
    }
}
