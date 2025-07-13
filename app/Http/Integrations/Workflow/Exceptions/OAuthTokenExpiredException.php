<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Http\Integrations\Workflow\Exceptions;

use RuntimeException;

class OAuthTokenExpiredException extends RuntimeException
{
    public static function make(): self
    {
        return new self('OAuth token is expired. Please subscribe to service again on "My Services" page');
    }
}
