<?php

namespace App\Listeners\WorkflowAction;

use App\Events\WorkflowAction\WorkflowActionTriggered;

class RefreshOAuthToken
{
    public function handle(WorkflowActionTriggered $event): void
    {
        $action = $event->action;

        $service = $action->serviceAction->service;
        $user = $action->workflow->user;
        /** @var \App\Models\ServiceSubscription $serviceSubscription */
        $serviceSubscription = $user->serviceSubscriptions()
            ->where('service_id', $action->serviceAction->service->id)
            ->first();

        $oauthToken = $serviceSubscription->oauthToken;
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
}
