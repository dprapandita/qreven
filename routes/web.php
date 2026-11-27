<?php

use App\Enums\EventStatus;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BoothBookingController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventMemberController;
use App\Http\Controllers\EventPublicController;
use App\Http\Controllers\RundownController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\TicketOrderController;
use App\Http\Controllers\TicketTypeController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    $events = collect();

    if (Schema::hasTable('events')) {
        $events = Event::query()
            ->where('status', EventStatus::Published->value)
            ->withMin('ticketTypes', 'harga')
            ->orderBy('tanggal_mulai')
            ->limit(6)
            ->get();
    }

    return view('welcome', compact('events'));
});

Route::domain('{event_subdomain}.'.config('events.base_domain'))->group(function () {
    Route::middleware('event.subdomain')->group(function () {
        Route::get('/', [EventPublicController::class, 'show'])->name('event.home');
        Route::get('/tickets', [EventPublicController::class, 'tickets'])->name('event.tickets');
    });

    Route::middleware(['auth', 'event.subdomain'])->group(function () {
        Route::post('/orders', [TicketOrderController::class, 'store'])
            ->middleware('throttle:20,1')
            ->name('event.orders.store');
        Route::post('/orders/{order}/cancel', [TicketOrderController::class, 'cancelOwn'])
            ->name('event.orders.cancel');
        Route::post('/check-in', [CheckInController::class, 'verify'])
            ->middleware('throttle:60,1')
            ->name('event.checkin');
        Route::post('/booths/book', [BoothBookingController::class, 'store'])
            ->middleware('throttle:20,1')
            ->name('event.booths.book');
        Route::post('/booth-bookings/{booking}/cancel', [BoothBookingController::class, 'cancelOwn'])
            ->name('event.booth-bookings.cancel');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('register.store');

    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
        ->whereIn('provider', ['google', 'github'])
        ->middleware('throttle:20,1')
        ->name('auth.redirect');

    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
        ->whereIn('provider', ['google', 'github'])
        ->middleware('throttle:20,1')
        ->name('auth.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/check-in', [CheckInController::class, 'index'])->name('checkin');
    Route::post('/check-in', [CheckInController::class, 'verifyForm'])->name('checkin.verify');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::prefix('/events/{event}')->group(function () {
        Route::get('/ticket-types/create', [TicketTypeController::class, 'create'])->name('ticket-types.create');
        Route::get('/ticket-types/{ticketType}/edit', [TicketTypeController::class, 'edit'])->name('ticket-types.edit');
        Route::get('/ticket-types', [TicketTypeController::class, 'index'])->name('ticket-types.index');
        Route::post('/ticket-types', [TicketTypeController::class, 'store'])->name('ticket-types.store');
        Route::put('/ticket-types/{ticketType}', [TicketTypeController::class, 'update'])->name('ticket-types.update');
        Route::delete('/ticket-types/{ticketType}', [TicketTypeController::class, 'destroy'])->name('ticket-types.destroy');

        Route::get('/booths/create', [BoothController::class, 'create'])->name('booths.create');
        Route::get('/booths/{booth}/edit', [BoothController::class, 'edit'])->name('booths.edit');
        Route::get('/booths', [BoothController::class, 'index'])->name('booths.index');
        Route::post('/booths', [BoothController::class, 'store'])->name('booths.store');
        Route::put('/booths/{booth}', [BoothController::class, 'update'])->name('booths.update');
        Route::delete('/booths/{booth}', [BoothController::class, 'destroy'])->name('booths.destroy');

        Route::get('/rundowns/create', [RundownController::class, 'create'])->name('rundowns.create');
        Route::post('/rundowns/store', [RundownController::class, 'store'])->name('rundowns.store');
        Route::get('/rundowns/{rundown}/edit', [RundownController::class, 'edit'])->name('rundowns.edit');
        Route::get('/rundowns', [RundownController::class, 'index'])->name('rundowns.index');
        Route::post('/rundowns', [RundownController::class, 'store'])->name('rundowns.store');
        Route::put('/rundowns/{rundown}', [RundownController::class, 'update'])->name('rundowns.update');
        Route::delete('/rundowns/{rundown}', [RundownController::class, 'destroy'])->name('rundowns.destroy');

        Route::get('/orders', [TicketOrderController::class, 'index'])->name('orders.index');
        Route::post('/orders/{order}/pay', [TicketOrderController::class, 'pay'])->name('orders.pay');
        Route::post('/orders/{order}/cancel', [TicketOrderController::class, 'cancel'])->name('orders.cancel');

        Route::get('/booth-bookings', [BoothBookingController::class, 'index'])->name('booth-bookings.index');
        Route::put('/booth-bookings/{booking}', [BoothBookingController::class, 'update'])->name('booth-bookings.update');
        Route::post('/booth-bookings/{booking}/approve', [BoothBookingController::class, 'approve'])->name('booth-bookings.approve');
        Route::post('/booth-bookings/{booking}/reject', [BoothBookingController::class, 'reject'])->name('booth-bookings.reject');
        Route::post('/booth-bookings/{booking}/cancel', [BoothBookingController::class, 'cancelByManager'])->name('booth-bookings.cancel');

        Route::get('/members', [EventMemberController::class, 'index'])->name('members.index');
        Route::post('/members', [EventMemberController::class, 'store'])->name('members.store');
        Route::delete('/members/{user}', [EventMemberController::class, 'destroy'])->name('members.destroy');
    });
});
