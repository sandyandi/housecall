<?php

declare(strict_types=1);

namespace App\Enums;

enum ReferralStatus: string
{
    case RECEIVED = 'received';
    case TRIAGING = 'triaging';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
}
