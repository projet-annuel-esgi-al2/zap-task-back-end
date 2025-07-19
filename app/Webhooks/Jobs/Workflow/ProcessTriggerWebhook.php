<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Webhooks\Jobs\Workflow;

use App\Actions\Workflow\ExecuteWorkflow;
use App\Enums\ServiceAction\Identifier;
use App\Enums\Workflow\Status;
use App\Models\Workflow;
use App\Models\WorkflowAction;
use App\Services\GoogleCalendar\CalendarEventObserver;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Uri;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessTriggerWebhook extends ProcessWebhookJob
{
    public function handle(): void
    {
        $workflowId = Uri::of($this->webhookCall->url)
            ->query()
            ->get('w');
        $deploymentId = Uri::of($this->webhookCall->url)
            ->query()
            ->get('d');

        $workflow = Workflow::where('id', $workflowId)
            ->where('deployment_id', $deploymentId)
            ->firstOrFail();

        if ($workflow->status !== Status::Deployed) {
            return;
        }

        if (empty($workflow->trigger)) {
            return;
        }

        if (Identifier::isGoogleTrigger($workflow->trigger->serviceAction->identifier)) {
            if (self::shouldHandleGoogleCalendarWorkflows($workflow)) {
                ExecuteWorkflow::dispatch($workflow);
            }

            return;
        }

        ExecuteWorkflow::dispatch($workflow);
    }

    private static function shouldHandleGoogleCalendarWorkflows(Workflow $workflow): bool
    {
        $user = $workflow->user;
        /** @var \App\Models\ServiceSubscription $serviceSubscription */
        $serviceSubscription = $user->serviceSubscriptions()
            ->where('service_id', $workflow->trigger->serviceAction->service->id)
            ->first();
        $oauthToken = $serviceSubscription->oauthToken;

        $observer = CalendarEventObserver::make($workflow->trigger, $oauthToken);

        $calendarId = Arr::get($workflow->trigger->url_parameters, 'calendarId');
        $numberOfSimultaneousTriggers = Cache::get($calendarId.$workflow->trigger->serviceAction->id);

        if (is_null($numberOfSimultaneousTriggers)) {
            $numberOfSimultaneousTriggers = WorkflowAction::where('service_action_id', $workflow->trigger->serviceAction->id)
                ->where('url_parameters->calendarId', $calendarId)
                ->count();
            Log::info('$numberOfSimultaneousTriggers = '.$numberOfSimultaneousTriggers);
            Cache::set($calendarId.$workflow->trigger->serviceAction->id, $numberOfSimultaneousTriggers);
        }

        if ($workflow->trigger->serviceAction->identifier === Identifier::GoogleCalendarEventCreated
            && $observer->hasNewlyCreatedEvent()) {
            $numberOfSimultaneousTriggers -= 1;

            if ($numberOfSimultaneousTriggers === 0) {
                Cache::forget($calendarId.$workflow->trigger->serviceAction->id);
                $observer->createOrRefreshSyncToken();
            }

            return true;
        } elseif ($workflow->trigger->serviceAction->identifier === Identifier::GoogleCalendarEventUpdated
            && $observer->hasUpdatedEvent()) {
            $numberOfSimultaneousTriggers -= 1;

            if ($numberOfSimultaneousTriggers === 0) {
                Cache::forget($calendarId.$workflow->trigger->serviceAction->id);
                $observer->createOrRefreshSyncToken();
            }

            return true;
        }

        return false;
    }
}
