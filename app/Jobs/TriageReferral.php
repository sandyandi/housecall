<?php

namespace App\Jobs;

use App\Models\Referral;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TriageReferral implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;
    public int $maxExceptions = 5;
    public int $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(private Referral $referral)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->referral->triage();
    }
}
