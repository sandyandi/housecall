<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ReferralStatus;
use App\Models\Referral;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Referral>
 */
class ReferralFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Referral>
     */
    protected $model = Referral::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'age' => $this->faker->randomNumber(2),
            'address' => $this->faker->address(),
            'reason' => $this->faker->sentence(),
            'priority' => $this->faker->numberBetween(2),
            'status' => ReferralStatus::RECEIVED,
        ];
    }
}
