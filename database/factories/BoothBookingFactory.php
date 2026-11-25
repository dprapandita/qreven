<?php

namespace Database\Factories;

use App\Enums\BoothBookingStatus;
use App\Models\Booth;
use App\Models\BoothBooking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BoothBooking>
 */
class BoothBookingFactory extends Factory
{
    protected $model = BoothBooking::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booth_id' => Booth::factory(),
            'user_id' => User::factory(),
            'status' => BoothBookingStatus::Pending->value,
            'catatan' => fake()->sentence(),
        ];
    }
}
