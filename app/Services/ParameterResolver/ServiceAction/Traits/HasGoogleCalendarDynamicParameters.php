<?php

/**
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Services\ParameterResolver\ServiceAction\Traits;

use App\Actions\WorkflowAction\RefreshOAuthToken;
use App\Http\Requests\Google\Calendar\GoogleCalendarConnector;
use App\Http\Requests\Google\Calendar\Requests\GetCalendars;
use App\Models\OAuthToken;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

trait HasGoogleCalendarDynamicParameters
{
    abstract public function oauthToken(): ?OAuthToken;

    public function calendarIds(): HtmlString
    {
        if (! $this->oauthToken()) {
            return new HtmlString('[]');
        }

        RefreshOAuthToken::run($this->oauthToken());

        $res = GoogleCalendarConnector::make($this->oauthToken()->value)
            ->send(GetCalendars::make());

        $calendarIds = collect($res->json('items'))
            ->filter(fn ($calendar) => Arr::get($calendar, 'accessRole') === 'owner')
            ->pluck('id')
            ->toArray();

        return new HtmlString(json_encode($calendarIds));
    }

    public function formatEmailsForGoogleCalendar($value): HtmlString
    {
        $emails = collect($value)
            ->mapWithKeys(fn ($email) => ['email' => $email])
            ->toArray();

        return new HtmlString(json_encode([$emails]));
    }

    public function formatToGoogleCalendarString(string $date): string
    {
        return Carbon::parse($date, 'Europe/Paris')
            ->toRfc3339String();
    }
}
