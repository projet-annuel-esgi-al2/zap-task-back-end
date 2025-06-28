<?php

namespace Tests\Traits;

use App\Models\User;

trait HasLoggedInUser
{
    public ?User $user;

    public function user(array $data = [], callable|bool $loggedIn = true): User
    {
        return $this->user ??= User::factory()
            ->loggedIn($loggedIn)
            ->createOne($data);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->user();
        auth()->login($this->user);

        $this->withHeaders([
            'Pat' => $this->user->latestAccessToken->token,
        ]);
    }
}
