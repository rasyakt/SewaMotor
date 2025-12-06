# SewaMotor

SewaMotor adalah aplikasi manajemen penyewaan motor berbasis Laravel. Aplikasi ini dibangun untuk memudahkan proses pemesanan, pembayaran, dan pengembalian motor oleh penyewa, serta mengizinkan admin dan pemilik motor untuk mengelola kendaraan dan transaksi.

> Dokumentasi ini mencakup panduan instalasi, arsitektur, fitur, routes utama, perintah artisan khusus, scheduler, dan panduan pengembangan.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi & Setup](#instalasi--setup)
- [Konfigurasi Environment (.env)](#konfigurasi-environment-env)
- [Database & Migrations](#database--migrations)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Arsitektur & Struktur Folder](#arsitektur--struktur-folder)
- [Routes Utama](#routes-utama)
- [Model & Relasi Penting](#model--relasi-penting)
- [Fitur Pengembalian Motor](#fitur-pengembalian-motor)
- [Perintah Artisan Khusus](#perintah-artisan-khusus)
- [Scheduler (Task dan Cron)](#scheduler-task-dan-cron)
- [Pengujian / Testing](#pengujian--testing)
- [Tips Deploy & Produksi](#tips-deploy--produksi)
- [Troubleshooting](#troubleshooting)
- [Kontribusi](#kontribusi)

---

## Fitur Utama

- Autentikasi (register/login) untuk tiga role: `admin`, `pemilik`, `penyewa`
- CRUD Motor, Tarif Rental, Penyewaan, Transaksi, Users
- Upload foto motor dan bukti pembayaran
- Proses pemesanan & pembayaran (transfer, cash, qris)
- Verifikasi admin untuk pembayaran cash
- Sistem pengembalian motor: manual (customer) & otomatis (scheduler)
- Perhitungan denda keterlambatan otomatis
- Antarmuka admin dengan sidebar navigasi dan ikon
- Halaman guest (login/register) dengan desain modern

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & npm / pnpm
- Laravel 11
- Database (MySQL, MariaDB, atau SQLite untuk development)
- Web server (Laragon, Nginx, Apache) atau gunakan `php artisan serve`

> Proyek ini dikembangkan pada Windows (Laragon) — instruksi deployment menyesuaikan.

## Instalasi & Setup

1. Clone repository

```powershell
cd C:\laragon\www
git clone https://github.com/rasyakt/SewaMotor.git
cd SewaMotor
```

2. Install dependensi

```powershell
composer install
npm install
# atau pnpm install
```

3. Copy `.env` dan generate APP_KEY

```powershell
copy .env.example .env
php artisan key:generate
```

4. Atur konfigurasi database di `.env` (contoh menggunakan SQLite yang sudah ada di `database/database.sqlite` atau MySQL)

5. Migrasi dan seeding

```powershell
php artisan migrate --seed
# atau
php artisan migrate:fresh --seed
```

6. Buat symbolic link untuk storage

```powershell
php artisan storage:link
```

7. Build assets

```powershell
npm run dev
# atau untuk produksi
npm run build
```

8. Jalankan server

```powershell
php artisan serve
# buka http://localhost:8000
```

## Konfigurasi Environment (.env)

Beberapa key penting di `.env`:

- `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MAIL_...` (opsional untuk notifikasi)
- `FILESYSTEM_DRIVER=public`

Pastikan `storage` dapat ditulis dan `storage:link` sudah dijalankan.

## Database & Migrations

Beberapa migrasi penting:

- `2025_11_07_072642_create_penyewaans_table.php` - tabel penyewaans
- `2025_11_07_072628_create_motors_table.php` - tabel motors
- `2025_12_05_120000_add_return_tracking_to_penyewaans_table.php` - menambah kolom pengembalian

Jalankan migrations:

```powershell
php artisan migrate
```

Jika ingin membersihkan dan seed:

```powershell
php artisan migrate:fresh --seed
```

## Menjalankan Aplikasi

- Development: `php artisan serve`
- Build assets: `npm run dev` atau `npm run build`

## Arsitektur & Struktur Folder

- `app/Models` - model Eloquent
- `app/Http/Controllers` - controllers (dibagi Admin/, Owner/, Customer/)
- `resources/views` - blade templates (layouts/, auth/, admin/, customer/, owner/)
- `routes/web.php` - routes web utama
- `app/Console/Commands` - artisan commands custom

### Layout penting

- `resources/views/layouts/app.blade.php` - layout utama (sidebar untuk user authenticated)
- `resources/views/layouts/guest.blade.php` - layout clean untuk login/register

## Routes Utama

- Auth routes: `Auth::routes()`
- Admin routes (middleware `role:admin`) - prefix `/admin`
- Owner routes (middleware `role:pemilik`) - prefix `/owner`
- Customer routes (middleware `role:penyewa`) - prefix `/customer`

Contoh route penting:

- `customer.bookings.return` (POST) - `POST /customer/bookings/{id}/return` untuk proses pengembalian manual

Lihat `routes/web.php` untuk daftar lengkap dan nama route.

## Model & Relasi Penting

- `User` - memiliki banyak `Penyewaan` jika role penyewa
- `Motor` - relasi ke `TarifRental`
- `Penyewaan` - relasi ke `User`, `Motor`, `Transaksi`, `BagiHasil`

Method penting di `Penyewaan`:
- `hitungDurasi()`
- `handleReturn($tanggalKembali, $statusRusak)`
- `isOverdue()`

## Fitur Pengembalian Motor

Fitur ini menangani pengembalian motor secara manual dan otomatis:

- Manual: customer mengklik "Kembalikan" di halaman history, mengisi kondisi (baik/rusak) dan catatan
- Otomatis: command `motor:check-return` memastikan penyewaan yang lewat tanggal selesai diproses

Skema kolom baru di tabel `penyewaans`:

```text
tanggal_kembali_aktual (date, nullable)
status_pengembalian (enum: pending, tepat_waktu, terlambat, rusak)
hari_terlambat (int)
denda_keterlambatan (decimal)
notifikasi_terkirim (boolean)
notifikasi_dikirim_at (timestamp)
```

### Perhitungan denda

```
Tarif harian = harga_total / hari_sewa
Denda per hari = 10% * tarif harian
Total denda = denda per hari * hari_terlambat
```

Jika ada denda, dibuat record `Transaksi` dengan metode `denda` dan status `pending`.

## Perintah Artisan Khusus

- `php artisan motor:check-return` - Menandai penyewaan yang melewati tanggal selesai dan memproses return

Jalankan manual untuk testing:

```powershell
php artisan motor:check-return
```

## Scheduler (Task dan Cron)

Scheduler di `app/Console/Kernel.php` menjadwalkan `motor:check-return` daily.

Menjalankan scheduler di Windows (development):

```powershell
php artisan schedule:work
# atau sekali jalan
php artisan schedule:run
```

Untuk produksi di Linux, pasang cron untuk menjalankan `php artisan schedule:run` tiap menit.

## Pengujian / Testing

Jalankan test:

```powershell
php artisan test
# atau
vendor\bin\phpunit
```

## Tips Deploy & Produksi

- Pastikan environment `APP_ENV=production` dan `APP_KEY` ter-set
- Jalankan `php artisan storage:link` dan atur permission pada folder `storage` dan `bootstrap/cache`
- Build assets: `npm run build`
- Setup web server untuk serve folder `public`
- Setup cron / task scheduler dan supervisor untuk queue worker jika diperlukan

## Troubleshooting

- Gambar tidak muncul: jalankan `php artisan storage:link` dan pastikan permission benar
- Error migrasi enum: jalankan migrasi yang menambah enum secara hati-hati
- Show/hide password tidak berfungsi: pastikan Font Awesome dan script `@push('scripts')` inclusion di layout guest

## Kontribusi

Silakan buka issue atau buat pull request. Ikuti standar PSR-12 dan sertakan test jika mengubah behavior penting.