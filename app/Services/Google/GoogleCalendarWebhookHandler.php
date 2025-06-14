<?php
namespace App\Services\Google;
use WebhookHandlerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class GoogleCalendarWebhookHandler implements WebhookHandlerInterface
{
    public function handle(Request $request): Response
    {   dd($request);
        Log::info('dans la fonction handle', []);
        if ($request->header('X-Goog-Resource-State') === 'sync') {
            return response('Sync received', 200);
        }

        Log::info('Webhook Google Calendar reçu', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        //  dispatch TriggerExecutionJob::dispatch($event)
        return response()->noContent();
    }
}
