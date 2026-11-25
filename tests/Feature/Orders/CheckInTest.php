<?php

use App\Enums\EventRole;
use App\Enums\TicketStatus;
use App\Models\Event;
use App\Models\TicketOrder;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

/**
 * @return array{0: User, 1: Event, 2: TicketType, 3: User}
 */
function checkInEventSet(): array
{
    $owner = User::factory()->create();
    $buyer = User::factory()->create();
    $event = Event::factory()->for($owner)->published()->create([
        'subdomain' => fake()->unique()->slug(2),
    ]);
    $ticketType = TicketType::factory()->for($event)->create([
        'harga' => 100000,
        'kuota' => 10,
        'capacity' => 10,
    ]);

    return [$owner, $event, $ticketType, $buyer];
}

function placeCheckInOrder(Event $event, TicketType $ticketType, User $buyer): TicketOrder
{
    test()->actingAs($buyer)->postJson('http://'.$event->subdomain.'.localhost/orders', [
        'ticket_type_id' => $ticketType->id,
        'jumlah' => 1,
    ])->assertCreated();

    return TicketOrder::query()->where('event_id', $event->id)->firstOrFail();
}

function payCheckInOrder(User $owner, Event $event, TicketOrder $order): void
{
    test()->actingAs($owner)
        ->postJson(route('orders.pay', [$event, $order]))
        ->assertOk();
}

it('checks in a paid ticket once', function () {
    [$owner, $event, $ticketType, $buyer] = checkInEventSet();
    $order = placeCheckInOrder($event, $ticketType, $buyer);

    payCheckInOrder($owner, $event, $order);

    $ticket = $order->tickets()->firstOrFail();

    $this->actingAs($owner)
        ->postJson('http://'.$event->subdomain.'.localhost/check-in', [
            'kode_tiket' => $ticket->kode_tiket,
        ])
        ->assertOk()
        ->assertJsonPath('message', 'Check-in berhasil');

    expect($ticket->refresh()->status)->toBe(TicketStatus::CheckedIn)
        ->and($ticket->checked_in_at)->not->toBeNull()
        ->and($ticket->checked_in_by)->toBe($owner->id);

    $this->actingAs($owner)
        ->postJson('http://'.$event->subdomain.'.localhost/check-in', [
            'kode_tiket' => $ticket->kode_tiket,
        ])
        ->assertStatus(422);
});

it('refuses to check in an unpaid ticket', function () {
    [$owner, $event, $ticketType, $buyer] = checkInEventSet();
    $order = placeCheckInOrder($event, $ticketType, $buyer);
    $ticket = $order->tickets()->firstOrFail();

    $this->actingAs($owner)
        ->postJson('http://'.$event->subdomain.'.localhost/check-in', [
            'kode_tiket' => $ticket->kode_tiket,
        ])
        ->assertStatus(422);

    expect($ticket->refresh()->checked_in_at)->toBeNull();
});

it('enforces check-in authorization roles', function () {
    [$owner, $event, $ticketType, $buyer] = checkInEventSet();
    $order = placeCheckInOrder($event, $ticketType, $buyer);
    payCheckInOrder($owner, $event, $order);
    $ticket = $order->tickets()->firstOrFail();

    $finance = User::factory()->create();
    $checkIn = User::factory()->create();
    $stranger = User::factory()->create();

    $event->members()->attach($finance->id, ['role' => EventRole::Finance->value]);
    $event->members()->attach($checkIn->id, ['role' => EventRole::CheckIn->value]);

    $this->actingAs($stranger)
        ->postJson('http://'.$event->subdomain.'.localhost/check-in', ['kode_tiket' => $ticket->kode_tiket])
        ->assertNotFound();

    $this->actingAs($finance)
        ->postJson('http://'.$event->subdomain.'.localhost/check-in', ['kode_tiket' => $ticket->kode_tiket])
        ->assertForbidden();

    $this->actingAs($checkIn)
        ->postJson('http://'.$event->subdomain.'.localhost/check-in', ['kode_tiket' => $ticket->kode_tiket])
        ->assertOk();
});

it('does not check in a ticket from another event', function () {
    [$ownerA, $eventA, $typeA, $buyerA] = checkInEventSet();
    [$ownerB, $eventB, $typeB, $buyerB] = checkInEventSet();

    $orderB = placeCheckInOrder($eventB, $typeB, $buyerB);
    payCheckInOrder($ownerB, $eventB, $orderB);
    $ticketB = $orderB->tickets()->firstOrFail();

    $this->actingAs($ownerA)
        ->postJson('http://'.$eventA->subdomain.'.localhost/check-in', [
            'kode_tiket' => $ticketB->kode_tiket,
        ])
        ->assertNotFound();
});

it('does not check in a ticket from a cancelled order', function () {
    [$owner, $event, $ticketType, $buyer] = checkInEventSet();
    $order = placeCheckInOrder($event, $ticketType, $buyer);

    $this->actingAs($buyer)
        ->postJson('http://'.$event->subdomain.'.localhost/orders/'.$order->id.'/cancel')
        ->assertOk();

    $ticket = $order->tickets()->firstOrFail();

    $this->actingAs($owner)
        ->postJson('http://'.$event->subdomain.'.localhost/check-in', [
            'kode_tiket' => $ticket->kode_tiket,
        ])
        ->assertStatus(422);
});
