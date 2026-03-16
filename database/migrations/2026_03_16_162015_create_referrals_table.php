<?php

declare(strict_types=1);

use App\Enums\ReferralPriority;
use App\Enums\ReferralStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->tinyInteger('age');
            $table->string('address');
            $table->text('reason');
            $table->string('priority')->default(ReferralPriority::LOW->value);
            $table->string('source');
            $table->text('notes')->nullable();
            $table->string('status')->default(ReferralStatus::RECEIVED->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
