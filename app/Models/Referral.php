<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReferralPriority;
use App\Enums\ReferralStatus;
use Database\Factories\ReferralFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
