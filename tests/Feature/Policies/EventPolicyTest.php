<?php

use App\Enums\EventRole;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('enforces the event permission matrix', function () {
    $owner = User::factory()->create();
    $administrator = User::factory()->create();
    $finance = User::factory()->create();
    $checkIn = User::factory()->create();
    $stranger = User::factory()->create();

    $event = Event::factory()->for($owner)->create();

    $event->members()->attach($administrator->id, ['role' => EventRole::Administrator->value]);
    $event->members()->attach($finance->id, ['role' => EventRole::Finance->value]);
    $event->members()->attach($checkIn->id, ['role' => EventRole::CheckIn->value]);

    expect($owner->can('view', $event))->toBeTrue()
        ->and($administrator->can('view', $event))->toBeTrue()
        ->and($finance->can('view', $event))->toBeTrue()
        ->and($checkIn->can('view', $event))->toBeTrue()
        ->and($stranger->can('view', $event))->toBeFalse();

    expect($owner->can('update', $event))->toBeTrue()
        ->and($administrator->can('update', $event))->toBeTrue()
        ->and($finance->can('update', $event))->toBeFalse()
        ->and($checkIn->can('update', $event))->toBeFalse()
        ->and($stranger->can('update', $event))->toBeFalse();

    expect($owner->can('delete', $event))->toBeTrue()
        ->and($administrator->can('delete', $event))->toBeFalse()
        ->and($finance->can('delete', $event))->toBeFalse();

    expect($owner->can('manageMembers', $event))->toBeTrue()
        ->and($administrator->can('manageMembers', $event))->toBeFalse();

    expect($owner->can('viewOrders', $event))->toBeTrue()
        ->and($administrator->can('viewOrders', $event))->toBeTrue()
        ->and($finance->can('viewOrders', $event))->toBeTrue()
        ->and($checkIn->can('viewOrders', $event))->toBeFalse()
        ->and($stranger->can('viewOrders', $event))->toBeFalse();

    expect($owner->can('manageOrders', $event))->toBeTrue()
        ->and($administrator->can('manageOrders', $event))->toBeFalse()
        ->and($finance->can('manageOrders', $event))->toBeTrue()
        ->and($checkIn->can('manageOrders', $event))->toBeFalse();

    expect($owner->can('checkIn', $event))->toBeTrue()
        ->and($administrator->can('checkIn', $event))->toBeTrue()
        ->and($finance->can('checkIn', $event))->toBeFalse()
        ->and($checkIn->can('checkIn', $event))->toBeTrue()
        ->and($stranger->can('checkIn', $event))->toBeFalse();
});
