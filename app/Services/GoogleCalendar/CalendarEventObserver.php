<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\GoogleCalendar;

use App\Actions\WorkflowAction\RefreshOAuthToken;
use App\Models\OAuthToken;
use App\Models\User;
use App\Models\WorkflowAction;
use App\Services\GoogleCalendar\Exceptions\NullCalendarIdException;
use App\Traits\Makeable;
use Google\Client;
use Google\Exception;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CalendarEventObserver
{
    use Makeable;

    private User $user;

    private Calendar $calendar;

    private string $calendarId;

    public function __construct(private readonly WorkflowAction $action, private readonly OAuthToken $oauthToken)
    {
        $calendarId = Arr::get($this->action->url_parameters, 'calendarId');

        if (is_null($calendarId)) {
            throw NullCalendarIdException::make($this->action);
        }
        $this->calendarId = $calendarId;

        $this->user = $this->action->workflow->user;
        RefreshOAuthToken::run($this->oauthToken);
        $client = new Client;
        $client->setAccessToken($this->oauthToken->value);
        $this->calendar = new Calendar($client);
    }

    public function createOrRefreshSyncToken(): void
    {
        $timeMin = now()->subMinute()->toRfc3339String();

        $params = [
            'singleEvents' => 'true',
            'maxResults' => 2500,
            'timeMin' => $timeMin,
        ];

        do {
            $events = $this->calendar->events->listEvents($this->calendarId, $params);
            $params['pageToken'] = $events->getNextPageToken();
        } while ($params['pageToken']);

        $this->user->googleSyncTokens()
            ->updateOrCreate(['calendar_id' => $this->calendarId], [
                'sync_token' => $events->getNextSyncToken(),
                'last_time_min_parameter' => $timeMin,
            ]);
    }

    /**
     * @return Collection<Event>
     *
     * @throws Exception
     * */
    private function getEvents()
    {
        /** @var \App\Models\GoogleCalendarSyncToken $syncToken */
        $syncToken = $this->user->googleSyncTokens()
            ->where('calendar_id', $this->calendarId)
            ->first();

        $params = [
            'syncToken' => $syncToken->sync_token,
        ];

        try {
            return collect($this->calendar->events->listEvents($this->calendarId, $params)->getItems());
        } catch (Exception $e) {
            if ($e->getCode() === Response::HTTP_GONE) {
                $this->createOrRefreshSyncToken();

                return collect($this->calendar->events->listEvents($this->calendarId, $params)->getItems());
            }

            throw $e;
        }
    }

    public function hasNewlyCreatedEvent(): bool
    {
        $events = $this->getEvents();

        $createdEvents = $events
            ->filter(fn (Event $event) => Carbon::parse($event->getCreated())->isSameAs('Y-m-d\TH:i:s', Carbon::parse($event->getUpdated())));
        Log::info('events count = '.json_encode($createdEvents->count()));

        return $createdEvents->count() !== 0;
    }

    public function hasUpdatedEvent(): bool
    {
        return $this->getEvents()
            ->filter(fn (Event $event) => ! Carbon::parse($event->getCreated())->isSameAs('Y-m-d\TH:i:s', Carbon::parse($event->getUpdated())))
            ->filter(fn (Event $event) => $event->getStatus() != 'cancelled')
            ->count() !== 0;
    }
}
