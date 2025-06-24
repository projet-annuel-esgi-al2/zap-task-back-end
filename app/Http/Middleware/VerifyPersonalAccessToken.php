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
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        $personalAccessToken = $request->header('Pat');

        if (! $personalAccessToken) {
            response()->json('No access token specified', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::query()
            ->whereHas('latestAccessToken', fn ($q) => $q->where('token', $personalAccessToken))
            ->first();

        if (is_null($user)) {
            response()->json('Invalid access token', Response::HTTP_UNAUTHORIZED);
        }

        Auth::login($user);

        return $next($request);
    }
}
