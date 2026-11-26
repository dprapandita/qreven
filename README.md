# Qreven

Qreven adalah aplikasi web untuk penyelenggara event. Proyek ini dirancang untuk mengelola acara, tiket, booth, pesanan, dan rundown dalam satu tempat. Penyelenggara dapat memantau event melalui dashboard, sedangkan halaman untuk pengunjung dan alur transaksi masih dikembangkan.

## Fitur yang sudah tersedia

- Registrasi dan login menggunakan email dan kata sandi.
- Login melalui Google dan GitHub menggunakan Laravel Socialite, jika kredensial OAuth sudah dikonfigurasi.
- Dashboard yang menampilkan jumlah event milik pengguna, pesanan tiket yang menunggu pembayaran, jumlah booth, dan event terbaru.
- Model dan migrasi untuk event, jenis tiket, pesanan tiket, tiket, booth, pemesanan booth, dan rundown.
- Controller untuk sejumlah operasi pengelolaan event, tiket, booth, dan check-in.

> **Status proyek:** Belum semua controller CRUD terhubung ke route web. Fitur pemesanan, pembayaran, dan check-in belum seluruhnya dapat digunakan melalui antarmuka. Subdomain khusus untuk tiap event masih direncanakan.

## Alur aplikasi

Penyelenggara mendaftar atau login, lalu masuk ke dashboard. Setiap event terhubung ke pemiliknya dan dapat memiliki jenis tiket, booth, serta rundown. Struktur data pesanan tiket dan pemesanan booth sudah disiapkan untuk pengembangan alur peserta dan transaksi.

## Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | PHP 8.3+ dan Laravel 13 |
| Antarmuka | Blade, Tailwind CSS, Vite |
| Login sosial | Laravel Socialite |
| Database | MariaDB |

## Menjalankan secara lokal

Siapkan PHP, Composer, Node.js/npm, dan MariaDB. Dari direktori proyek, jalankan:

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
```

Atur koneksi database di file `.env`:

```dotenv
APP_NAME=Qreven
APP_URL=http://localhost:8000

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qreven
DB_USERNAME=qreven
DB_PASSWORD=isi_password_lokal
```

Buat database dan pengguna MariaDB sesuai konfigurasi tersebut. Setelah itu, jalankan:

```bash
php artisan migrate
npm run build
php artisan serve
```

Buka `http://localhost:8000/register` untuk membuat akun. Saat mengembangkan tampilan, jalankan `npm run dev` di terminal lain.

### Login Google dan GitHub

Login sosial bersifat opsional. Isi variabel berikut di `.env` jika ingin menggunakannya:

```dotenv
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
```

Daftarkan URL callback yang sama pada pengaturan OAuth Google dan GitHub.

## Struktur proyek

- `app/Models` — model data dan relasinya.
- `app/Http/Controllers` — autentikasi, dashboard, dan logika fitur event.
- `database/migrations` — struktur tabel database.
- `resources/views` — halaman Blade.
- `routes/web.php` — route web yang aktif.

