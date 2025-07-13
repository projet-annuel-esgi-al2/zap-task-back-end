<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Webhooks;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator as SpatieSignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class SignatureValidator implements SpatieSignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        return true;
    }
}
