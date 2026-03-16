<?php

declare(strict_types=1);

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enums\ReferralPriority;
use App\Enums\ReferralStatus;
use App\Http\Middlewares\CheckIdempotency;
use App\Jobs\TriageReferral;
use Database\Factories\ReferralFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            middleware: [
                'throttle:create-referral',
                CheckIdempotency::class,
            ],
        ),
        new Get(),
        new Patch(
            uriTemplate: '/referrals/{id}/cancel',
        // Custom cancellation logic will be handled by a State Processor
        ),
    ],
    routePrefix: '/api/v1',
    rules: [
        'name' => 'required|string|min:3',
        'age' => 'required|integer:strict',
        'address' => 'required|string|min:3',
        'reason' => 'required|string|min:3',
        'priority' => 'string',
        'source' => 'required|string|min:3',
    ],
)]
class Referral extends Model
{
    /** @use HasFactory<ReferralFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'address',
        'reason',
        'priority',
        'source',
        'notes',
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    protected static function booted(): void
    {
        static::created(function (self $referral) {
            Log::info('Referral created', ['referral' => $referral->toArray()]);
            TriageReferral::dispatch($referral);
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => ReferralPriority::class,
            'status' => ReferralStatus::class,
        ];
    }

    public function triage(): void
    {
        if ($this->status !== ReferralStatus::RECEIVED) {
            Log::info('Skipping triage for referral', ['referral' => $this->toArray()]);
            return;
        }

        Log::info('Triaging referral', ['referral' => $this->toArray()]);
        $this->status = ReferralStatus::TRIAGING;
        $this->save();

        $status = match($this->priority) {
            ReferralPriority::HIGH, ReferralPriority::MEDIUM => ReferralStatus::ACCEPTED,
            ReferralPriority::LOW => ReferralStatus::REJECTED,
        };

        $this->status = $status;
        $this->save();
        Log::info('Triage complete: referral status set to: ' . $status->value, ['referral' => $this->toArray()]);
    }
}
