<?php

use App\Enums\EventRole;
use App\Enums\TicketOrderStatus;
use App\Enums\TicketStatus;
use App\Jobs\ExpireTicketOrder;
use App\Models\Event;
use App\Models\TicketOrder;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

/**
 * @return array{0: User, 1: Event, 2: TicketType}
 */
function orderEvent(int $kuota = 10, int $harga = 100000): array
{
    $owner = User::factory()->create();
    $event = Event::factory()->for($owner)->published()->create([
        'subdomain' => fake()->unique()->slug(2),
    ]);
    $ticketType = TicketType::factory()->for($event)->create([
        'harga' => $harga,
        'kuota' => $kuota,
        'capacity' => $kuota,
    ]);

    return [$owner, $event, $ticketType];
}

it('creates a pending order with items, tickets, and reserved inventory', function () {
    [$owner, $event, $ticketType] = orderEvent();
    $buyer = User::factory()->create();

    $response = $this->actingAs($buyer)->postJson(
        'http://'.$event->subdomain.'.localhost/orders',
        ['ticket_type_id' => $ticketType->id, 'jumlah' => 2],
    );

    $response->assertCreated()
        ->assertJsonPath('data.status_pembayaran', TicketOrderStatus::Pending->value)
        ->assertJsonPath('data.total_harga', '200000.00');

    $order = TicketOrder::query()->where('event_id', $event->id)->firstOrFail();

    expect($order->user_id)->toBe($buyer->id)
        ->and($order->expires_at)->not->toBeNull()
        ->and($order->items()->count())->toBe(1)
        ->and($order->tickets()->count())->toBe(2)
        ->and($ticketType->refresh()->kuota)->toBe(8);

    $this->assertDatabaseHas('ticket_order_items', [
        'ticket_order_id' => $order->id,
        'ticket_type_id' => $ticketType->id,
        'quantity' => 2,
        'unit_price' => '100000.00',
        'subtotal' => '200000.00',
    ]);
});

it('marks a free order as paid immediately', function () {
    [$owner, $event, $ticketType] = orderEvent(harga: 0);
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders', [
            'ticket_type_id' => $ticketType->id,
            'jumlah' => 1,
        ])
        ->assertCreated()
        ->assertJsonPath('data.status_pembayaran', TicketOrderStatus::Paid->value)
        ->assertJsonPath('data.total_harga', '0.00');

    $order = TicketOrder::query()->where('event_id', $event->id)->firstOrFail();

    expect($order->paid_at)->not->toBeNull()
        ->and($order->expires_at)->toBeNull();
});

it('rejects an order that exceeds remaining quota', function () {
    [$owner, $event, $ticketType] = orderEvent(kuota: 1);
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders', [
            'ticket_type_id' => $ticketType->id,
            'jumlah' => 2,
        ])
        ->assertStatus(422);

    $this->assertDatabaseCount('ticket_orders', 0);
    $this->assertDatabaseCount('tickets', 0);
    expect($ticketType->refresh()->kuota)->toBe(1);
});

it('rejects a ticket type from another event', function () {
    [$owner, $event] = orderEvent();
    [$otherOwner, $otherEvent, $otherType] = orderEvent();
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders', [
            'ticket_type_id' => $otherType->id,
            'jumlah' => 1,
        ])
        ->assertStatus(422);

    $this->assertDatabaseCount('ticket_orders', 0);
    expect($otherType->refresh()->kuota)->toBe(10);
});

it('does not sell tickets for a draft event to a non-member', function () {
    $owner = User::factory()->create();
    $event = Event::factory()->for($owner)->create(['subdomain' => 'draft-order']);
    $type = TicketType::factory()->for($event)->create(['capacity' => 10]);
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->postJson('http://draft-order.localhost/orders', [
            'ticket_type_id' => $type->id,
            'jumlah' => 1,
        ])
        ->assertNotFound();
});

it('confirms payment for a pending order', function () {
    [$owner, $event, $ticketType] = orderEvent();
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders', [
            'ticket_type_id' => $ticketType->id,
            'jumlah' => 1,
        ])
        ->assertCreated();

    $order = TicketOrder::query()->where('event_id', $event->id)->firstOrFail();

    $this->actingAs($owner)
        ->postJson(route('orders.pay', [$event, $order]))
        ->assertOk()
        ->assertJsonPath('data.status_pembayaran', TicketOrderStatus::Paid->value);

    expect($order->refresh()->paid_at)->not->toBeNull();
});

it('allows finance members to confirm payment but not administrators', function () {
    [$owner, $event, $ticketType] = orderEvent();
    $buyer = User::factory()->create();
    $finance = User::factory()->create();
    $administrator = User::factory()->create();

    $event->members()->attach($finance->id, ['role' => EventRole::Finance->value]);
    $event->members()->attach($administrator->id, ['role' => EventRole::Administrator->value]);

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders', [
            'ticket_type_id' => $ticketType->id,
            'jumlah' => 1,
        ]);

    $order = TicketOrder::query()->where('event_id', $event->id)->firstOrFail();

    $this->actingAs($administrator)
        ->postJson(route('orders.pay', [$event, $order]))
        ->assertForbidden();

    $this->actingAs($finance)
        ->postJson(route('orders.pay', [$event, $order]))
        ->assertOk();
});

it('lets the buyer cancel a pending order and restores inventory exactly once', function () {
    [$owner, $event, $ticketType] = orderEvent();
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders', [
            'ticket_type_id' => $ticketType->id,
            'jumlah' => 2,
        ]);

    $order = TicketOrder::query()->where('event_id', $event->id)->firstOrFail();

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders/'.$order->id.'/cancel')
        ->assertOk();

    expect($order->refresh()->status_pembayaran)->toBe(TicketOrderStatus::Cancelled)
        ->and($order->inventory_released_at)->not->toBeNull()
        ->and($ticketType->refresh()->kuota)->toBe(10)
        ->and($order->tickets()->where('status', TicketStatus::Cancelled->value)->count())->toBe(2);

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders/'.$order->id.'/cancel')
        ->assertStatus(422);

    expect($ticketType->refresh()->kuota)->toBe(10);
});

it('expires a pending order and releases inventory exactly once', function () {
    [$owner, $event, $ticketType] = orderEvent();
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders', [
            'ticket_type_id' => $ticketType->id,
            'jumlah' => 3,
        ]);

    $order = TicketOrder::query()->where('event_id', $event->id)->firstOrFail();

    $this->travel(31)->minutes();

    (new ExpireTicketOrder($order->refresh()))->handle();

    expect($order->refresh()->status_pembayaran)->toBe(TicketOrderStatus::Expired)
        ->and($order->inventory_released_at)->not->toBeNull()
        ->and($ticketType->refresh()->kuota)->toBe(10);

    (new ExpireTicketOrder($order->refresh()))->handle();

    expect($ticketType->refresh()->kuota)->toBe(10);

    $this->travelBack();
});

it('rejects payment for an expired order and releases its inventory', function () {
    [$owner, $event, $ticketType] = orderEvent();
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders', [
            'ticket_type_id' => $ticketType->id,
            'jumlah' => 2,
        ]);

    $order = TicketOrder::query()->where('event_id', $event->id)->firstOrFail();

    $this->travel(31)->minutes();

    $this->actingAs($owner)
        ->postJson(route('orders.pay', [$event, $order]))
        ->assertStatus(422);

    expect($order->refresh()->status_pembayaran)->toBe(TicketOrderStatus::Expired)
        ->and($ticketType->refresh()->kuota)->toBe(10);

    $this->travelBack();
});

it('forbids a non-member from viewing event orders', function () {
    [$owner, $event] = orderEvent();

    $this->actingAs(User::factory()->create())
        ->getJson(route('orders.index', $event))
        ->assertNotFound();
});
