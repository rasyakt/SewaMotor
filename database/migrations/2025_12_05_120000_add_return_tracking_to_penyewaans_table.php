<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            // Tambah kolom untuk tracking pengembalian
            $table->date('tanggal_kembali_aktual')->nullable()->after('tanggal_selesai');
            $table->enum('status_pengembalian', ['pending', 'tepat_waktu', 'terlambat', 'rusak'])->default('pending')->after('status');
            $table->integer('hari_terlambat')->default(0)->after('status_pengembalian');
            $table->decimal('denda_keterlambatan', 12, 2)->default(0)->after('hari_terlambat');
            
            // Untuk tracking notifikasi
            $table->boolean('notifikasi_terkirim')->default(false)->after('denda_keterlambatan');
            $table->timestamp('notifikasi_dikirim_at')->nullable()->after('notifikasi_terkirim');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_kembali_aktual',
                'status_pengembalian',
                'hari_terlambat',
                'denda_keterlambatan',
                'notifikasi_terkirim',
                'notifikasi_dikirim_at',
            ]);
        });
    }
};
