<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Integrations\OAuthToken;

use App\Models\Service;
use Saloon\Contracts\OAuthAuthenticator;
use Saloon\Http\Response;

interface OAuthRefresherContract
{
    public static function fromService(Service $service): self;

    public function refreshAccessToken(OAuthAuthenticator|string $refreshToken, bool $returnResponse = false, ?callable $requestModifier = null): OAuthAuthenticator|Response;
}
