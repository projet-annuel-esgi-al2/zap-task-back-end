<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        return response()->json([
            'url' => $client->createAuthUrl()
        ]);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $token = $client->fetchAccessTokenWithAuthCode($request->code);
        return response()->json($token);
    }

    public function showGoogleCalendarEvents(Request $request)
    {
        $client = new Google_Client();
        $service = new Google_Service_Calendar($client);

        $now        = now();
        $startOfWeek= $now->startOfWeek()->toRfc3339String();
        $endOfWeek  = $now->endOfWeek()->toRfc3339String();

        $events = $service->events->listEvents('primary', [
            'timeMin' => $startOfWeek,
            'timeMax' => $endOfWeek,
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ])->getItems();

        $payload = array_map(fn($e) => [
            'id'    => $e->getId(),
            'summary' => $e->getSummary(),
            'start' => $e->getStart()->getDateTime() ?: $e->getStart()->getDate(),
            'end'   => $e->getEnd()->getDateTime()   ?: $e->getEnd()->getDate(),
        ], $events);

        return response()->json($payload);
    }
}
