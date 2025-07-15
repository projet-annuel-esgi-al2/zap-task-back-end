<?php

namespace App\Actions\WorkflowAction;

use App\Events\WorkflowAction\WorkflowActionTriggered;
use App\Models\OAuthToken;
use Lorisleiva\Actions\Concerns\AsAction;

class RefreshOAuthToken
{
    use AsAction;

    public function handle(OAuthToken $oauthToken)
    {
        $service = $oauthToken->serviceSubscription->service;
        $refreshToken = $oauthToken->refresh_token;

        if (empty($refreshToken) || ! $oauthToken->expired()) {
            return;
        }

        $serviceConfigAccessor = 'services.'.$service->socialite_driver_identifier;

        /** @var \App\Http\Integrations\OAuthToken\OAuthRefresherContract $tokenRefresherClass */
        $tokenRefresherClass = config($serviceConfigAccessor.'.token_refresher');

        $tokenRefresherConnector = $tokenRefresherClass::fromService($service);

        $res = $tokenRefresherConnector->refreshAccessToken($refreshToken, returnResponse: true);

        $accessTokenAttributeName = config($serviceConfigAccessor.'.refresh_token_response_data.access_token');
        $expiresInAttributeName = config($serviceConfigAccessor.'.refresh_token_response_data.expires_in');
        $oauthToken->update([
            'value' => $res->json($accessTokenAttributeName),
            'expires_at' => now()->addSeconds($res->json($expiresInAttributeName)),
        ]);
    }

    public function asListener(WorkflowActionTriggered $event): void
    {
        $action = $event->action;

        $user = $action->workflow->user;
        /** @var \App\Models\ServiceSubscription $serviceSubscription */
        $serviceSubscription = $user->serviceSubscriptions()
            ->where('service_id', $action->serviceAction->service->id)
            ->first();

        $this->handle($serviceSubscription->oauthToken);
    }
}
