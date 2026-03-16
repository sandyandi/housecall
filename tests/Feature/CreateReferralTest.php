<?php

namespace Tests\Feature;

use ApiPlatform\Laravel\Test\ApiTestAssertionsTrait;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateReferralTest extends TestCase
{
    use ApiTestAssertionsTrait, RefreshDatabase;

    public function testUnauthenticatedUsersAreNotAllowedToAccessReferrals()
    {
        $this
            ->getJson('/api/v1/referrals')
            ->assertUnauthorized();
    }

    public function testAuthenticatedUsersAreAllowedToAccessReferrals()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this
            ->withHeader('authorization', 'bearer ' . $token)
            ->getJson('/api/v1/referrals')
            ->assertOk();
    }

    public function testReferralCreationFailsIfIdempotencyKeyIsNotPresent()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this
            ->withHeader('authorization', 'bearer ' . $token)
            ->postJson('/api/v1/referrals', [
                'name' => 'John Doe',
                'age' => 40,
                'address' => 'Some Address',
                'reason' => 'Some reason',
                'priority' => 'medium',
                'source' => 'Some Source',
            ])
            ->assertBadRequest();
    }

    public function testReferralCreationSucceedsIfIdempotencyKeyIsPresent()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this
            ->withHeader('authorization', 'bearer ' . $token)
            ->withHeader('idempotency-key', 'key1')
            ->postJson('/api/v1/referrals', [
                'name' => 'John Doe',
                'age' => 40,
                'address' => 'Some Address',
                'reason' => 'Some reason',
                'priority' => 'medium',
                'source' => 'Some Source',
            ])
            ->assertCreated();
    }

    public function testDuplicatedReferralCreationIsPreventedForTheSameIdempotencyKey()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this
            ->withHeader('authorization', 'bearer ' . $token)
            ->withHeader('idempotency-key', 'key1')
            ->postJson('/api/v1/referrals', [
                'name' => 'John Doe',
                'age' => 40,
                'address' => 'Some Address',
                'reason' => 'Some reason',
                'priority' => 'medium',
                'source' => 'Some Source',
            ])
            ->assertCreated();

        $this
            ->withHeader('authorization', 'bearer ' . $token)
            ->withHeader('idempotency-key', 'key1')
            ->postJson('/api/v1/referrals', [
                'name' => 'John Doe',
                'age' => 40,
                'address' => 'Some Address',
                'reason' => 'Some reason',
                'priority' => 'medium',
                'source' => 'Some Source',
            ])
            ->assertOk();
    }
}
