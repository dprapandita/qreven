<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketType>
 */
class TicketTypeFactory extends Factory
{
    protected $model = TicketType::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'nama_tiket' => fake()->randomElement(['Reguler', 'VIP', 'Presale']),
            'harga' => 100000,
            'kuota' => 10,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (TicketType $ticketType): void {
            $ticketType->capacity ??= $ticketType->kuota;
        });
    }
}
