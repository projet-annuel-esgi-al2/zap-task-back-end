<?php

namespace App\Http\Controllers;

use App\Enums\Service\Identifier;
use App\Models\OAuthToken;
use App\Models\Service;
use App\Models\ServiceSubscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class ServiceOAuthController extends Controller
{
    /**
     * @group Services OAuth
     *
     * Check If User Is Subscribed To Service
     *
     * @authenticated
     *
     * @response 404 { "message": "User is not subscribed to this service."}
     * @response 200
     */
    public function get(Identifier $serviceIdentifier): JsonResponse
    {
        if (! auth()->user()?->subscribedToService($serviceIdentifier)) {
            return response()
                ->json('User is not subscribed to this service', Response::HTTP_NOT_FOUND)
                ->withHeaders(['Location' => route('service-oauth-redirect', ['serviceIdentifier' => $serviceIdentifier])]);
        }

        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @group Services OAuth
     *
     * Fetch Service's OAuth Consent Screen
     *
     * @authenticated
     *
     * @response 302
     */
    public function redirect(Identifier $serviceIdentifier): JsonResponse
    {
        $service = Service::firstWhere('identifier', $serviceIdentifier->value);

        $socialiteDriverIdentifier = $service->socialite_driver_identifier;
        $scopes = $service->scopes()->pluck('scope_value')->toArray();

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $encodedData = self::encodeStateData($user, $service->id);

        /** @phpstan-ignore-next-line */
        $socialite = Socialite::driver($socialiteDriverIdentifier)
            ->stateless()
            ->with(['state' => $encodedData])
            ->scopes($scopes);

        if (! empty($service->oauth_token_options)) {
            $socialite->with([
                ...$service->oauth_token_options,
                'state' => $encodedData,
            ]);
        }

        return response()->json([
            'url' => $socialite
                ->redirectUrl(route('service-oauth-callback'))
                ->redirect()
                ->getTargetUrl(),
        ]);
    }

    /**
     * @hideFromAPIDocumentation
     * */
    public function callback(Request $request): View
    {
        $data = self::decodeStateData($request->query('state'));

        $service = Service::find(Arr::get($data, 'service_id'));
        /** @phpstan-ignore-next-line */
        $oauthUser = Socialite::driver($service->socialite_driver_identifier)
            ->stateless()
            ->redirectUrl(route('service-oauth-callback'))
            ->user();

        $oauthToken = OAuthToken::create([
            'user_id' => Arr::get($data, 'user_id'),
            'value' => $oauthUser->token,
            'refresh_token' => $oauthUser->refreshToken,
            'expires_at' => now()->addSeconds($oauthUser->expiresIn),
        ]);

        ServiceSubscription::create([
            'service_id' => $service->id,
            'oauth_token_id' => $oauthToken->id,
        ]);

        return view('close');
    }

    private static function encodeStateData(User $user, string $serviceId): string
    {
        return base64_encode(json_encode(['user_id' => $user->id, 'service_id' => $serviceId]));
    }

    private static function decodeStateData(string $encodedData): array
    {
        return json_decode(base64_decode($encodedData), true);
    }
}
