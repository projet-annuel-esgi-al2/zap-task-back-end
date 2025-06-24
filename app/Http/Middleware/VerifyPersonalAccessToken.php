<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyPersonalAccessToken
{
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        $personalAccessToken = $request->header('Pat');

        if (! $personalAccessToken) {
            return response()->json('No access token specified', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::query()
            ->whereHas('latestAccessToken', fn ($q) => $q->where('token', $personalAccessToken))
            ->first();

        if (is_null($user)) {
            return response()->json('Invalid access token', Response::HTTP_UNAUTHORIZED);
        }

        Auth::login($user);

        return $next($request);
    }
}
