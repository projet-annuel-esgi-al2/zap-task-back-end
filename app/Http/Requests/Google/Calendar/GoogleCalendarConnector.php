<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

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
            ->setClientId(config('services.google.client_id'))
            ->setClientSecret(config('services.google.client_secret'))
            ->setRedirectUri(config('services.google.redirect_uri'))
            ->setDefaultScopes(config('services.google.calendar.default_scopes'))
            ->setAuthorizeEndpoint(config('services.google.calendar.auth_url'))
            ->setTokenEndpoint(config('services.google.calendar.token_url'))
            ->setUserEndpoint(config('services.google.calendar.user_info_url'));
    }
}
