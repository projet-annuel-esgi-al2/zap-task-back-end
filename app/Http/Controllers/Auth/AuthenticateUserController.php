<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalAccessTokenResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthenticateUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();

        if (is_null($user) || ! Hash::check($request->input('password'), $user->password)) {
            abort(Response::HTTP_UNAUTHORIZED, 'Incorrect email or password');
        }

        return response()->json(PersonalAccessTokenResource::make($user->latestAccessToken));
    }
}
