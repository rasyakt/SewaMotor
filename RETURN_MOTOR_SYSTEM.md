# Fitur Pengembalian Motor (Return Motor)

## Overview
Sistem pengembalian motor otomatis yang mengelola pengembalian motor sesuai dengan jangka waktu penyewaan yang telah ditentukan.

## Fitur Utama

### 1. **Auto-Check Return Status**
- Sistem secara otomatis mengecek dan menandai motor yang sudah melewati tanggal selesai
- Command: `php artisan motor:check-return`
- Dijalankan setiap hari jam 00:00 (via scheduler)

### 2. **Manual Return Motor (Customer Side)**
- Customer dapat mengembalikan motor secara manual dari halaman History Penyewaan
- Pilihan kondisi motor: Baik atau Rusak
- Catatan tambahan untuk dokumentasi

### 3. **Automatic Late Fee Calculation**
- Denda keterlambatan otomatis dihitung berdasarkan:
  - Hari terlambat
  - Tarif harian motor (10% dari tarif harian per hari)
- Denda otomatis dibuat sebagai record Transaksi dengan status "pending"

### 4. **Return Status Tracking**
Motor dapat dikembalikan dengan status:
- `tepat_waktu` - Motor dikembalikan sesuai jadwal
- `terlambat` - Motor dikembalikan lebih dari tanggal selesai + informasi denda
- `rusak` - Motor dikembalikan dalam kondisi rusak
- `pending` - Belum dikembalikan

## Database Schema

Kolom baru di tabel `penyewaans`:
```sql
- tanggal_kembali_aktual (date, nullable) - Tanggal motor benar-benar dikembalikan
- status_pengembalian (enum) - Status pengembalian (pending, tepat_waktu, terlambat, rusak)
- hari_terlambat (integer) - Jumlah hari keterlambatan
- denda_keterlambatan (decimal) - Nominal denda
- notifikasi_terkirim (boolean) - Flag notifikasi
- notifikasi_dikirim_at (timestamp) - Waktu notifikasi dikirim
```

## Model Methods

### Penyewaan Model
```php
// Handle return motor
handleReturn($tanggalKembali = null, $statusRusak = false)
- Memproses pengembalian motor
- Menghitung status dan denda
- Update motor status ke 'tersedia'

// Check apakah overdue
isOverdue()
- Return true jika sudah melewati tanggal selesai dan belum dikembalikan

// Get label status
getStatusPengembalianLabel()
- Return label dalam bahasa Indonesia
```

## Controller Methods

### CustomerController
```php
returnMotor(Request $request, $id)
- Handle manual return dari customer
- Validasi kondisi motor
- Buat Transaksi denda jika ada keterlambatan
```

## Command Execution

Jalankan command manual (untuk testing):
```bash
php artisan motor:check-return
```

Output contoh:
```
🚍 Mengecek pengembalian motor yang sudah melewati waktu...
Ditemukan 2 penyewaan yang melewati waktu.
⏰ Penyewaan #1 - Motor: Honda CB150R
   Penyewa: Budi Santoso
   Terlambat: 3 hari
   ✅ Status diperbarui menjadi: Terlambat
   💰 Denda Keterlambatan: Rp 75.000

✓ Pengecekan selesai!
```

## Routes

```php
// Return motor (POST)
POST /customer/bookings/{id}/return
- Route: customer.bookings.return
- Require: kondisi_motor (baik/rusak), catatan (optional)
```

## UI Components

### Return Modal
- Menampilkan info motor dan tanggal selesai
- Badge alert jika sudah terlambat
- Radio button untuk pilih kondisi motor
- Textarea untuk catatan

### History Status Display
Menampilkan:
- Status Penyewaan (pending, dibayar, dikonfirmasi, selesai, dibatalkan)
- Status Pengembalian (tepat_waktu, terlambat, rusak, belum_dikembalikan)
- Info denda jika ada

## Business Logic

1. **Saat Pengembalian**:
   - Customer klik "Kembalikan" button (hanya untuk status 'dikonfirmasi')
   - Pilih kondisi motor
   - Sistem menghitung status pengembalian
   - Jika terlambat, denda otomatis dihitung
   - Motor status update ke 'tersedia'
   - Penyewaan status update ke 'selesai'

2. **Auto-Check (Scheduler)**:
   - Setiap hari jam 00:00
   - Check semua penyewaan yang belum selesai
   - Jika sudah melewati tanggal selesai:
     - Mark sebagai terlambat
     - Hitung denda
     - Update motor status ke 'tersedia'

3. **Denda Calculation**:
   - Formula: (harga_total / hari_sewa) * 0.1 * hari_terlambat
   - Contoh: Harga total Rp 750.000 / 5 hari = Rp 150.000/hari
   - Denda/hari = 10% × Rp 150.000 = Rp 15.000
   - Jika 3 hari terlambat = Rp 15.000 × 3 = Rp 45.000

## Testing

### Manual Test
1. Buat penyewaan dengan tanggal selesai hari ini
2. Tunggu waktu terlewat, atau manual test dengan command
3. Cek halaman history - status akan update otomatis

### Command Test
```bash
php artisan motor:check-return
```

## Future Enhancements

- [ ] Notifikasi real-time ke customer saat motor terlambat
- [ ] Auto-reminder email/SMS sebelum tanggal selesai
- [ ] Payment gateway untuk denda keterlambatan
- [ ] Admin dashboard untuk manage return requests
- [ ] Image upload untuk dokumentasi kondisi motor
- [ ] Insurance claim untuk motor rusak
