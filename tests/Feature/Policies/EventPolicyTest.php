<?php

use App\Enums\EventRole;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('enforces simple event access rules', function () {
    $owner = User::factory()->create();
    $administrator = User::factory()->create();
    $finance = User::factory()->create();
    $checkIn = User::factory()->create();
    $stranger = User::factory()->create();

    $event = Event::factory()->for($owner)->create();

    $event->members()->attach($administrator->id, ['role' => EventRole::Administrator->value]);
    $event->members()->attach($finance->id, ['role' => EventRole::Finance->value]);
    $event->members()->attach($checkIn->id, ['role' => EventRole::CheckIn->value]);

    expect($event->isAccessibleBy($owner))->toBeTrue()
        ->and($event->isAccessibleBy($administrator))->toBeTrue()
        ->and($event->isAccessibleBy($finance))->toBeTrue()
        ->and($event->isAccessibleBy($checkIn))->toBeTrue()
        ->and($event->isAccessibleBy($stranger))->toBeFalse();

    expect($event->canBeManagedBy($owner))->toBeTrue()
        ->and($event->canBeManagedBy($administrator))->toBeTrue()
        ->and($event->canBeManagedBy($finance))->toBeFalse()
        ->and($event->canBeManagedBy($checkIn))->toBeFalse()
        ->and($event->canBeManagedBy($stranger))->toBeFalse();

    expect($event->isOwnedBy($owner))->toBeTrue()
        ->and($event->isOwnedBy($administrator))->toBeFalse()
        ->and($event->isOwnedBy($finance))->toBeFalse();

    expect($event->canManageMembersBy($owner))->toBeTrue()
        ->and($event->canManageMembersBy($administrator))->toBeFalse();

    expect($event->canManageOrdersBy($owner))->toBeTrue()
        ->and($event->canManageOrdersBy($administrator))->toBeTrue()
        ->and($event->canManageOrdersBy($finance))->toBeTrue()
        ->and($event->canManageOrdersBy($checkIn))->toBeFalse()
        ->and($event->canManageOrdersBy($stranger))->toBeFalse();

    expect($event->canCheckInBy($owner))->toBeTrue()
        ->and($event->canCheckInBy($administrator))->toBeTrue()
        ->and($event->canCheckInBy($finance))->toBeFalse()
        ->and($event->canCheckInBy($checkIn))->toBeTrue()
        ->and($event->canCheckInBy($stranger))->toBeFalse();
});
