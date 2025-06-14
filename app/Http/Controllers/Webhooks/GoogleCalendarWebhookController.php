<?php
namespace App\Http\Controllers\Webhooks;

use App\Services\Contracts\WebhookHandlerInterface;
use App\Services\Google\GoogleCalendarWebhookHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleCalendarWebhookController
{
    protected $handler;

    public function __construct(GoogleCalendarWebhookHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(Request $request)
    {    Log::info('Webhook handler reçu');

        abort(200, 'Webhook controller reached'); // test immédiat

        return $this->handler->handle($request);
    }
}
