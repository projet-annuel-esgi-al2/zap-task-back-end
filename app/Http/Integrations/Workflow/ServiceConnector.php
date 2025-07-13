<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Integrations\Workflow;

use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class ServiceConnector extends Connector
{
    use AcceptsJson;
    use AuthorizationCodeGrant;

    public function resolveBaseUrl(): string
    {
        return '';
    }
}
