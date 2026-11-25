<?php

namespace Database\Factories;

use App\Models\TicketOrder;
use App\Models\TicketOrderItem;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketOrderItem>
 */
class TicketOrderItemFactory extends Factory
{
    protected $model = TicketOrderItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_order_id' => TicketOrder::factory(),
            'ticket_type_id' => TicketType::factory(),
            'quantity' => 1,
            'unit_price' => 100000,
            'subtotal' => 100000,
        ];
    }
}
