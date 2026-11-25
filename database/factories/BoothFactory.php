<?php

namespace Database\Factories;

use App\Enums\BoothStatus;
use App\Models\Booth;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booth>
 */
class BoothFactory extends Factory
{
    protected $model = Booth::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'nama_booth' => 'Booth '.fake()->unique()->numberBetween(1, 999),
            'deskripsi' => fake()->sentence(),
            'harga' => 500000,
            'status' => BoothStatus::Available->value,
        ];
    }
}
