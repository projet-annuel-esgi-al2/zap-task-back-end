<?php

namespace App\Http\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleCalendarWebhookController
{
    public function __invoke(Request $request)
    {

        if ($request->header('X-Goog-Resource-State') === 'sync') {
            return response('Sync received', 200);
        }

        // logs p
        Log::info('Webhook Google Calendar reçu', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        // actions au futur ici

        return response()->noContent(); // 204
    }
}
