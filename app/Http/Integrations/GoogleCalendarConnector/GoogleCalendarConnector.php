<?php

namespace App\Http\Integrations\GoogleCalendarConnector;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class GoogleCalendarConnector extends Connector
{
    use AuthorizationCodeGrant;
    use AcceptsJson;

    public function resolveBaseUrl(): string
    {
        return 'https://www.googleapis.com/calendar/v3';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId(config('app.google_client_id', env('GOOGLE_CLIENT_ID')))
            ->setClientSecret(config('app.google_client_secret', env('GOOGLE_CLIENT_SECRET')))
            ->setRedirectUri(config('app.google_redirect_uri', env('GOOGLE_REDIRECT_URI')))
            ->setDefaultScopes([
                'https://www.googleapis.com/auth/calendar',
                'https://www.googleapis.com/auth/calendar.events'
            ])
            ->setAuthorizeEndpoint('https://accounts.google.com/o/oauth2/v2/auth')
            ->setTokenEndpoint('https://oauth2.googleapis.com/token')
            ->setUserEndpoint('https://openidconnect.googleapis.com/v1/userinfo');
    }
}
