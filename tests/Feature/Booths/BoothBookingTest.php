<?php

use App\Enums\BoothBookingStatus;
use App\Enums\BoothStatus;
use App\Enums\EventRole;
use App\Models\Booth;
use App\Models\BoothBooking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

/**
 * @return array{0: User, 1: Event, 2: Booth}
 */
function boothEventSet(): array
{
    $owner = User::factory()->create();
    $event = Event::factory()->for($owner)->published()->create([
        'subdomain' => fake()->unique()->slug(2),
    ]);
    $booth = Booth::factory()->for($event)->create();

    return [$owner, $event, $booth];
}

it('creates a pending booth booking for a tenant', function () {
    [$owner, $event, $booth] = boothEventSet();
    $tenant = User::factory()->create();

    $this->actingAs($tenant)
        ->postJson('http://'.$event->subdomain.'.localhost/booths/book', [
            'booth_id' => $booth->id,
            'catatan' => 'Butuh listrik',
        ])
        ->assertCreated()
        ->assertJsonPath('data.status', BoothBookingStatus::Pending->value);

    $this->assertDatabaseHas('booth_bookings', [
        'booth_id' => $booth->id,
        'user_id' => $tenant->id,
        'status' => BoothBookingStatus::Pending->value,
    ]);
});

it('prevents a duplicate active booking by the same tenant', function () {
    [$owner, $event, $booth] = boothEventSet();
    $tenant = User::factory()->create();

    $this->actingAs($tenant)->postJson('http://'.$event->subdomain.'.localhost/booths/book', [
        'booth_id' => $booth->id,
    ])->assertCreated();

    $this->actingAs($tenant)->postJson('http://'.$event->subdomain.'.localhost/booths/book', [
        'booth_id' => $booth->id,
    ])->assertStatus(422);

    $this->assertDatabaseCount('booth_bookings', 1);
});

it('rejects a booth from another event', function () {
    [$owner, $event] = boothEventSet();
    [$otherOwner, $otherEvent, $otherBooth] = boothEventSet();
    $tenant = User::factory()->create();

    $this->actingAs($tenant)
        ->postJson('http://'.$event->subdomain.'.localhost/booths/book', [
            'booth_id' => $otherBooth->id,
        ])
        ->assertStatus(422);
});

it('approves a booking and marks the booth as booked', function () {
    [$owner, $event, $booth] = boothEventSet();
    $tenant = User::factory()->create();
    $booking = BoothBooking::factory()->for($booth)->for($tenant)->create();

    $this->actingAs($owner)
        ->postJson(route('booth-bookings.approve', [$event, $booking->id]))
        ->assertOk()
        ->assertJsonPath('data.status', BoothBookingStatus::Approved->value);

    expect($booking->refresh()->approved_at)->not->toBeNull()
        ->and($booth->refresh()->status)->toBe(BoothStatus::Booked);
});

it('does not approve two bookings for the same booth', function () {
    [$owner, $event, $booth] = boothEventSet();
    $first = BoothBooking::factory()->for($booth)->create();
    $second = BoothBooking::factory()->for($booth)->create();

    $this->actingAs($owner)
        ->postJson(route('booth-bookings.approve', [$event, $first->id]))
        ->assertOk();

    $this->actingAs($owner)
        ->postJson(route('booth-bookings.approve', [$event, $second->id]))
        ->assertStatus(422);
});

it('rejects a pending booking without changing the booth', function () {
    [$owner, $event, $booth] = boothEventSet();
    $booking = BoothBooking::factory()->for($booth)->create();

    $this->actingAs($owner)
        ->postJson(route('booth-bookings.reject', [$event, $booking->id]))
        ->assertOk();

    expect($booking->refresh()->status)->toBe(BoothBookingStatus::Rejected)
        ->and($booth->refresh()->status)->toBe(BoothStatus::Available);
});

it('releases the booth when an approved booking is cancelled', function () {
    [$owner, $event, $booth] = boothEventSet();
    $booking = BoothBooking::factory()->for($booth)->create();

    $this->actingAs($owner)->postJson(route('booth-bookings.approve', [$event, $booking->id]))->assertOk();

    $this->actingAs($owner)
        ->postJson(route('booth-bookings.cancel', [$event, $booking->id]))
        ->assertOk();

    expect($booking->refresh()->status)->toBe(BoothBookingStatus::Cancelled)
        ->and($booking->cancelled_at)->not->toBeNull()
        ->and($booth->refresh()->status)->toBe(BoothStatus::Available);
});

it('lets a tenant cancel their own pending booking', function () {
    [$owner, $event, $booth] = boothEventSet();
    $tenant = User::factory()->create();
    $booking = BoothBooking::factory()->for($booth)->for($tenant)->create();

    expect($booking->booth->event_id)->toBe($event->id)
        ->and(BoothBooking::query()->whereKey($booking->id)->whereHas('booth', fn ($query) => $query->where('event_id', $event->id))->exists())->toBeTrue();

    $this->withoutExceptionHandling();

    $this->actingAs($tenant)
        ->postJson('http://'.$event->subdomain.'.localhost/booth-bookings/'.$booking->id.'/cancel')
        ->assertOk();

    expect($booking->refresh()->status)->toBe(BoothBookingStatus::Cancelled);
});

it('enforces booth booking authorization', function () {
    [$owner, $event, $booth] = boothEventSet();
    $finance = User::factory()->create();
    $stranger = User::factory()->create();
    $booking = BoothBooking::factory()->for($booth)->create();

    $event->members()->attach($finance->id, ['role' => EventRole::Finance->value]);

    $this->actingAs($stranger)
        ->postJson(route('booth-bookings.approve', [$event, $booking->id]))
        ->assertNotFound();

    $this->actingAs($finance)
        ->postJson(route('booth-bookings.approve', [$event, $booking->id]))
        ->assertForbidden();
});

it('does not delete a booth with booking history', function () {
    [$owner, $event, $booth] = boothEventSet();
    BoothBooking::factory()->for($booth)->create();

    $this->actingAs($owner)
        ->deleteJson(route('booths.destroy', [$event, $booth->id]))
        ->assertStatus(422);

    $this->assertModelExists($booth);
});

it('soft deletes a booth without booking history', function () {
    [$owner, $event, $booth] = boothEventSet();

    $this->actingAs($owner)
        ->deleteJson(route('booths.destroy', [$event, $booth->id]))
        ->assertOk();

    $this->assertSoftDeleted($booth);
});
