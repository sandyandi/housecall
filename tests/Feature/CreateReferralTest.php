<?php

use App\Models\Referral;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('successfully creates referral with idempotency key header', function () {
    $payload = [
        'name' => 'John Doe',
        'age' => 40,
        'address' => 'Some address',
        'reason' => 'Some reason',
        'priority' => 'low',
    ];

    $response = $this
        ->withHeader('Idempotency-Key', 'key1')
        ->withHeader('Content-Type', 'application/ld+json')
        ->post('/api/v1/referrals', $payload);

    $response->assertStatus(201);
    
    $this->assertDatabaseHas('referrals', [
        'name' => 'John Doe',
        'age' => 40,
    ]);
});


