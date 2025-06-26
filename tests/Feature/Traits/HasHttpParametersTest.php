<?php

namespace Tests\Feature\Traits;

use App\Models\ServiceAction;
use Tests\TestCase;

class HasHttpParametersTest extends TestCase
{
    public function test_can_fetch_correct_body_parameters(): void
    {
        /** @var \App\Models\ServiceAction $serviceAction */
        $serviceAction = ServiceAction::factory()
            ->createOne(['body_parameters' => self::oneHiddenOneNotHiddenParameters()]);

        $this->assertEqualsCanonicalizing(
            expected: [
                [
                    'parameter_name' => 'User Email',
                    'parameter_type' => 'email',
                ],
            ],
            actual : $serviceAction->body_parameters_for_api,
        );

        $serviceAction->updateQuietly(['body_parameters' => self::bothNotHiddenParameters()]);
        $this->assertEqualsCanonicalizing(
            expected: [
                [
                    'parameter_name' => 'User Email',
                    'parameter_type' => 'email',
                ],
                [
                    'parameter_name' => 'User Password',
                    'parameter_type' => 'password',
                ],
            ],
            actual : $serviceAction->body_parameters_for_api,
        );

        $serviceAction->updateQuietly(['body_parameters' => self::bothHiddenParameters()]);
        $this->assertEmpty($serviceAction->body_parameters_for_api);
    }

    public function test_can_fetch_correct_query_parameters(): void
    {
        /** @var \App\Models\ServiceAction $serviceAction */
        $serviceAction = ServiceAction::factory()
            ->createOne(['query_parameters' => self::oneHiddenOneNotHiddenParameters()]);

        $this->assertEqualsCanonicalizing(
            expected: [
                [
                    'parameter_name' => 'User Email',
                    'parameter_type' => 'email',
                ],
            ],
            actual : $serviceAction->query_parameters_for_api,
        );

        $serviceAction->updateQuietly(['query_parameters' => self::bothNotHiddenParameters()]);
        $this->assertEqualsCanonicalizing(
            expected: [
                [
                    'parameter_name' => 'User Email',
                    'parameter_type' => 'email',
                ],
                [
                    'parameter_name' => 'User Password',
                    'parameter_type' => 'password',
                ],
            ],
            actual : $serviceAction->query_parameters_for_api,
        );

        $serviceAction->updateQuietly(['query_parameters' => self::bothHiddenParameters()]);
        $this->assertEmpty($serviceAction->query_parameters_for_api);
    }

    public function test_can_fetch_correct_url_parameters(): void
    {
        /** @var \App\Models\ServiceAction $serviceAction */
        $serviceAction = ServiceAction::factory()
            ->createOne(['url_parameters' => self::oneHiddenOneNotHiddenParameters()]);

        $this->assertEqualsCanonicalizing(
            expected: [
                [
                    'parameter_name' => 'User Email',
                    'parameter_type' => 'email',
                ],
            ],
            actual : $serviceAction->url_parameters_for_api,
        );

        $serviceAction->updateQuietly(['url_parameters' => self::bothNotHiddenParameters()]);
        $this->assertEqualsCanonicalizing(
            expected: [
                [
                    'parameter_name' => 'User Email',
                    'parameter_type' => 'email',
                ],
                [
                    'parameter_name' => 'User Password',
                    'parameter_type' => 'password',
                ],
            ],
            actual : $serviceAction->url_parameters_for_api,
        );

        $serviceAction->updateQuietly(['url_parameters' => self::bothHiddenParameters()]);
        $this->assertEmpty($serviceAction->url_parameters_for_api);
    }

    public static function oneHiddenOneNotHiddenParameters(): array
    {
        return [
            [
                'parameter_name' => 'User Email',
                'parameter_type' => 'email',
                'hidden' => false,
            ],
            [
                'hidden' => true,
                'key' => 'ttl',
                'value' => 604800,
            ],
        ];
    }

    public static function bothNotHiddenParameters(): array
    {
        return [
            [
                'parameter_name' => 'User Email',
                'parameter_type' => 'email',
                'hidden' => false,
            ],
            [
                'parameter_name' => 'User Password',
                'parameter_type' => 'password',
                'hidden' => false,
            ],
        ];
    }

    public static function bothHiddenParameters(): array
    {
        return [
            [
                'parameter_name' => 'User Email',
                'parameter_type' => 'email',
                'hidden' => true,
            ],
            [
                'parameter_name' => 'User Password',
                'parameter_type' => 'password',
                'hidden' => true,
            ],
        ];
    }
}
