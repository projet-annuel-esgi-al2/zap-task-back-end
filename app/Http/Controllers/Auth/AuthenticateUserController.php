<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalAccessTokenResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            abort(Response::HTTP_UNAUTHORIZED, 'Incorrect email or password');
        }

        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        if ($user->latestAccessToken()->doesntExist()) {
            abort(Response::HTTP_UNAUTHORIZED, 'No access token');
        }

        Auth::login($user);

        return response()->json(PersonalAccessTokenResource::make($user->latestAccessToken));
    }
}
