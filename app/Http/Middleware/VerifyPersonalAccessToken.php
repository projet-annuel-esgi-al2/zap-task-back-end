<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPersonalAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $personalAccessToken = $request->header('Pat');

        if (is_null($personalAccessToken)) {
            abort(Response::HTTP_UNAUTHORIZED, 'No access token specified');
        }

        $isInvalidAccessToken = User::query()
            ->whereHas('latestAccessToken', fn ($q) => $q->where('token', $personalAccessToken))
            ->doesntExist();

        if ($isInvalidAccessToken) {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid access token');
        }

        return $next($request);
    }
}
