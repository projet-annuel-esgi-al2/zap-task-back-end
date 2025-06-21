<?php

namespace App\Http\Requests\Google\Calendar;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class GoogleCalendarConnector extends Connector
{
    use AcceptsJson;
    use AuthorizationCodeGrant;

    public function resolveBaseUrl(): string
    {
        return config('services.google.calendar.base_url');
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
            ->setDefaultScopes(config('services.google.calendar.default_scopes'))
            ->setAuthorizeEndpoint(config('services.google.calendar.auth_url'))
            ->setTokenEndpoint(config('services.google.calendar.token_url'))
            ->setUserEndpoint(config('services.google.calendar.user_info_url'));
    }
}
