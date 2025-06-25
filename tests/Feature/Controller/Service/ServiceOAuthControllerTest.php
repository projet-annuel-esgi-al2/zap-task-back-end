<?php

namespace Tests\Feature\Controller\Service;

use App\Models\Service;
use App\Models\ServiceSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ServiceOAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_not_found_when_service_identifier_query_parameter_is_invalid(): void
    {
        $service = Service::factory()->createOne();

        $this->assertDatabaseHas('services', ['identifier' => $service->identifier]);

        $this->get('/api/subscriptions/fake-invalid-identifier')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_returns_success_when_user_is_subscribed_to_service(): void
    {
        /** @var \App\Models\ServiceSubscription $serviceSubscription */
        $serviceSubscription = ServiceSubscription::factory()->createOne();
        /** @var \App\Models\User $user */
        $user = $serviceSubscription->oauthToken->user;
        auth()->login($user);

        $this->withHeaders([
            'Pat' => $user->latestAccessToken->token,
        ])
            ->get('/api/subscriptions/'.$serviceSubscription->service->identifier->value)
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_returns_not_found_when_user_is_not_subscribed_to_service(): void
    {
        /** @var \App\Models\Service $service */
        $service = Service::factory()->createOne();
        /** @var \App\Models\User $user */
        $user = User::factory()->loggedIn()->createOne();
        auth()->login($user);

        $this->withHeaders([
            'Pat' => $user->latestAccessToken->token,
        ])
            ->get('/api/subscriptions/'.$service->identifier->value)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertHeader('Location', route('service-oauth-redirect', ['serviceIdentifier' => $service->identifier->value]));
    }
}
