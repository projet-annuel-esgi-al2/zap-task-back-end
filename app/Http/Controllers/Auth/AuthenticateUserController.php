<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalAccessTokenResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json('Incorrect email or password', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        if ($user->latestAccessToken()->doesntExist()) {
            return response()->json('No access token', Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(PersonalAccessTokenResource::make($user->latestAccessToken));
    }
}
