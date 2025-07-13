<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Integrations\OAuthToken\Connectors;

use App\Http\Integrations\OAuthToken\OAuthRefresherContract;
use App\Models\Service;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class GoogleOAuthConnector extends Connector implements OAuthRefresherContract
{
    use AcceptsJson;
    use AuthorizationCodeGrant;

    public function __construct(protected array $scopes) {}

    public function resolveBaseUrl(): string
    {
        return '';
    }

    public static function fromService(Service $service): self
    {
        return self::make($service->scopes->pluck('scope_value')->toArray());
    }

    /**
     * The OAuth2 configuration
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId(config('services.google.client_id'))
            ->setClientSecret(config('services.google.client_secret'))
            ->setRedirectUri(config('services.google.redirect'))
            ->setDefaultScopes($this->scopes)
            ->setAuthorizeEndpoint(config('services.google.auth_url'))
            ->setTokenEndpoint(config('services.google.token_url'));
    }
}
