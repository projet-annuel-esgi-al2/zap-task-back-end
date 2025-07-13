<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

return [
    'configs' => [
        [
            'name' => 'trigger-workflow-webhook',
            'signing_secret' => env('WEBHOOK_CLIENT_SECRET'),
            'signature_header_name' => 'Signature',
            'signature_validator' => \App\Webhooks\SignatureValidator::class,
            'webhook_profile' => \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [
                'X-Goog-Channel-Token',
                'X-Goog-Channel-Id',
            ],
            'process_webhook_job' => \App\Webhooks\Jobs\Workflow\ProcessTriggerWebhook::class,
        ],
    ],

    /*
     * The integer amount of days after which models should be deleted.
     *
     * It deletes all records after 30 days. Set to null if no models should be deleted.
     */
    'delete_after_days' => 30,

    /*
     * Should a unique token be added to the route name
     */
    'add_unique_token_to_route_name' => false,
];
