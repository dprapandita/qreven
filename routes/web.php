<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes (dari rekan tim)
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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// 2. Events List (dengan dummy data untuk testing view)
Route::get('/events', function () {
    $events = collect([
        (object)[
            'id' => 1,
            'nama_event' => 'Neon Nights Electro Festival',
            'deskripsi' => 'Festival musik elektronik terbesar dengan visual laser dan sound system kelas dunia.',
            'lokasi' => 'Gelora Bung Karno, Jakarta',
            'tanggal_mulai' => '2026-12-15',
            'tanggal_selesai' => '2026-12-16',
            'status' => 'aktif',
            'poster' => null,
        ],
        (object)[
            'id' => 2,
            'nama_event' => 'Tech Innovators Summit 2026',
            'deskripsi' => 'Konferensi teknologi & startup terdepan menghadirkan pakar AI dan Web3 dari seluruh Asia.',
            'lokasi' => 'ICE BSD, Tangerang',
            'tanggal_mulai' => '2026-12-20',
            'tanggal_selesai' => '2026-12-21',
            'status' => 'upcoming',
            'poster' => null,
        ],
        (object)[
            'id' => 3,
            'nama_event' => 'Indie Arts & Creative Pop-Up',
            'deskripsi' => 'Pameran karya seni indie, live acoustic performance, dan kurasi booth UMKM kreatif.',
            'lokasi' => 'Taman Ismail Marzuki, Jakarta',
            'tanggal_mulai' => '2026-12-28',
            'tanggal_selesai' => '2026-12-29',
            'status' => 'aktif',
            'poster' => null,
        ],
    ]);

    return view('events.index', compact('events'));
})->name('events');

// 3. Tambah Event Baru
Route::get('/events/create', function () {
    return view('events.create');
})->name('events.create');

// 4. Detail Event
Route::get('/events/{id}', function ($id) {
    $event = (object)[
        'id' => $id,
        'nama_event' => 'Neon Nights Electro Festival',
        'deskripsi' => 'Festival musik elektronik spektakuler dengan 3 panggung utama, instalasi visual interaktif, dan penampilan eksklusif dari DJ internasional.',
        'lokasi' => 'Gelora Bung Karno, Jakarta',
        'tanggal_mulai' => '2026-12-15',
        'tanggal_selesai' => '2026-12-16',
        'status' => 'aktif',
        'poster' => null,
        'ticketTypes' => collect([
            (object)['id' => 1, 'nama_tiket' => 'Early Bird VIP', 'harga' => 350000, 'kuota' => 50],
            (object)['id' => 2, 'nama_tiket' => 'Regular General Admission', 'harga' => 175000, 'kuota' => 200],
        ]),
        'booths' => collect([
            (object)['id' => 1, 'nama_booth' => 'F&B Zone A1', 'deskripsi' => 'Area makanan dan minuman dekat stage utama', 'harga' => 1500000, 'status' => 'tersedia'],
            (object)['id' => 2, 'nama_booth' => 'Merch Stand B2', 'deskripsi' => 'Stand merchandise resmi dekat pintu masuk', 'harga' => 2000000, 'status' => 'dipesan'],
        ]),
        'rundowns' => collect([
            (object)['id' => 1, 'nama_kegiatan' => 'Gate Open & Registration', 'waktu_mulai' => '15:00', 'waktu_selesai' => '16:30', 'deskripsi' => 'Pemeriksaan tiket dan akses masuk penonton'],
            (object)['id' => 2, 'nama_kegiatan' => 'Opening Act: DJ Nova', 'waktu_mulai' => '17:00', 'waktu_selesai' => '19:00', 'deskripsi' => 'Pemanasan panggung utama'],
            (object)['id' => 3, 'nama_kegiatan' => 'Main Show & Laser Display', 'waktu_mulai' => '20:00', 'waktu_selesai' => '23:00', 'deskripsi' => 'Pertunjukan utama dan penutupan'],
        ]),
    ];

    return view('events.show', compact('event'));
})->name('events.show');

// 5. Booths
Route::get('/booths', function () {
    $booths = collect([
        (object)['id' => 1, 'nama_booth' => 'Booth A1 - Food & Drinks', 'deskripsi' => 'Lokasi strategis dekat panggung utama dengan fasilitas listrik 1300W.', 'harga' => 1500000, 'status' => 'tersedia', 'event' => (object)['nama_event' => 'Neon Nights Electro Festival']],
        (object)['id' => 2, 'nama_booth' => 'Booth B3 - Fashion & Apparel', 'deskripsi' => 'Area pameran pakaian dan aksesoris kreatif anak muda.', 'harga' => 1200000, 'status' => 'dipesan', 'event' => (object)['nama_event' => 'Indie Arts Pop-Up']],
        (object)['id' => 3, 'nama_booth' => 'Booth C1 - Tech Showcase', 'deskripsi' => 'Cocok untuk demo produk software dan gadget startup.', 'harga' => 2500000, 'status' => 'tersedia', 'event' => (object)['nama_event' => 'Tech Innovators Summit']],
    ]);

    return view('booths.index', compact('booths'));
})->name('booths');

// 6. Tiket
Route::get('/tickets', function () {
    $ticketTypes = collect([
        (object)['id' => 1, 'nama_tiket' => 'VIP All Access Pass', 'harga' => 500000, 'kuota' => 100, 'event' => (object)['nama_event' => 'Neon Nights Electro Festival']],
        (object)['id' => 2, 'nama_tiket' => 'General Admission - Day 1', 'harga' => 150000, 'kuota' => 450, 'event' => (object)['nama_event' => 'Neon Nights Electro Festival']],
        (object)['id' => 3, 'nama_tiket' => 'Student Pass (Diskon)', 'harga' => 85000, 'kuota' => 75, 'event' => (object)['nama_event' => 'Tech Innovators Summit']],
    ]);

    return view('ticket-types.index', compact('ticketTypes'));
})->name('tickets');

// 7. Rundown
Route::get('/rundown', function () {
    $rundowns = collect([
        (object)['id' => 1, 'nama_kegiatan' => 'Gate Open & Welcome Session', 'waktu_mulai' => '2026-12-15 15:00', 'waktu_selesai' => '2026-12-15 16:30', 'deskripsi' => 'Registrasi tiket, pembagian wristband, dan open gate.', 'event' => (object)['nama_event' => 'Neon Nights Electro Festival']],
        (object)['id' => 2, 'nama_kegiatan' => 'Opening Ceremony & DJ Set 1', 'waktu_mulai' => '2026-12-15 17:00', 'waktu_selesai' => '2026-12-15 19:00', 'deskripsi' => 'Penampilan pembuka dari artis lokal dan visual mapping.', 'event' => (object)['nama_event' => 'Neon Nights Electro Festival']],
        (object)['id' => 3, 'nama_kegiatan' => 'Main Headliner & Fireworks', 'waktu_mulai' => '2026-12-15 20:30', 'waktu_selesai' => '2026-12-15 23:00', 'deskripsi' => 'Puncak acara musik dan pesta kembang api.', 'event' => (object)['nama_event' => 'Neon Nights Electro Festival']],
    ]);

    return view('rundowns.index', compact('rundowns'));
})->name('rundown');

// 8. Check-in Scanner
Route::get('/checkin', function () {
    return view('checkin.index');
})->name('checkin');

