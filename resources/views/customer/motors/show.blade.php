@extends('layouts.app')

@section('title', 'Detail Motor - ' . $motor->merk)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative">
                       <img src="{{ asset('storage/' . $motor->photo) }}" class="img-fluid rounded" 
                           onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';"
                                 alt="{{ $motor->merk }}" style="max-height: 400px; width: 100%; object-fit: cover;">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-success fs-6">
                                <i class="fas fa-check-circle"></i> Tersedia
                            </span>
                        </div>
                        
                        <!-- Thumbnail Gallery -->
                        <div class="row mt-3">
                            <div class="col-3">
                                <img src="{{ asset('storage/' . $motor->photo) }}" onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';" 
                                     class="img-thumbnail cursor-pointer" 
                                     style="height: 80px; object-fit: cover;"
                                     onclick="changeMainImage(this.src)">
                            </div>
                            <!-- Add more thumbnails if available -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-primary">{{ $motor->merk }}</h2>
                        <p class="text-muted fs-5">{{ $motor->tipe_cc }} CC • {{ $motor->no_plat }}</p>
                        
                        <div class="mb-4">
                            <h5 class="text-success">Tarif Rental:</h5>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-primary mb-1">Harian</h6>
                                        <h5 class="text-success mb-0">Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}</h5>
                                        <small class="text-muted">per hari</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-primary mb-1">Mingguan</h6>
                                        <h5 class="text-success mb-0">Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}</h5>
                                        <small class="text-muted">per minggu</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <h6 class="text-primary mb-1">Bulanan</h6>
                                        <h5 class="text-success mb-0">Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}</h5>
                                        <small class="text-muted">per bulan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($motor->deskripsi)
                        <div class="mb-4">
                            <h5>Deskripsi:</h5>
                            <p class="text-muted">{{ $motor->deskripsi }}</p>
                        </div>
                        @endif

                        <!-- Spesifikasi -->
                        <div class="mb-3">
                            <h5>Spesifikasi:</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><i class="fas fa-tachometer-alt text-primary"></i> <strong>CC:</strong> {{ $motor->tipe_cc }} CC</p>
                                    <p class="mb-1"><i class="fas fa-id-card text-primary"></i> <strong>Plat:</strong> {{ $motor->no_plat }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><i class="fas fa-check-circle text-success"></i> <strong>Status:</strong> Tersedia</p>
                                    <p class="mb-1"><i class="fas fa-shield-alt text-primary"></i> <strong>Terverifikasi</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Tambahan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Syarat & Ketentuan:</h6>
                        <ul class="small">
                            <li>Usia minimal 17 tahun</li>
                            <li>Memiliki SIM C yang masih berlaku</li>
                            <li>KTM/Kartu Pelajar untuk pelajar</li>
                            <li>DP minimal 50% untuk booking</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Fasilitas:</h6>
                        <ul class="small">
                            <li>Motor dalam kondisi prima</li>
                            <li>Bensin penuh saat pengambilan</li>
                            <li>Helm standar (2 buah)</li>
                            <li>Surat-surat lengkap</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Form Sidebar -->
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Form Penyewaan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customer.motors.book', $motor->id) }}" method="POST" id="bookingForm">
                    @csrf

                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                               id="tanggal_mulai" name="tanggal_mulai" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                               value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                               id="tanggal_selesai" name="tanggal_selesai" 
                               min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                               value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tipe_durasi" class="form-label">Tipe Durasi</label>
                        <select class="form-control @error('tipe_durasi') is-invalid @enderror" 
                                id="tipe_durasi" name="tipe_durasi" required>
                            <option value="">Pilih Durasi</option>
                            <option value="harian" {{ old('tipe_durasi') == 'harian' ? 'selected' : '' }}>Harian</option>
                            <option value="mingguan" {{ old('tipe_durasi') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                            <option value="bulanan" {{ old('tipe_durasi') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                        @error('tipe_durasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price Calculation -->
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <h6>Detail Biaya:</h6>
                            <div id="calculationDetails">
                                <p class="mb-1">Pilih tanggal dan durasi untuk melihat biaya</p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Biaya:</strong>
                                <strong id="totalBiaya">-</strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                            <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card mt-3">
            <div class="card-body text-center">
                <h6><i class="fas fa-headset"></i> Butuh Bantuan?</h6>
                <p class="small text-muted mb-2">Hubungi customer service kami</p>
                <a href="https://wa.me/6281234567890" class="btn btn-success btn-sm" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="tel:081234567890" class="btn btn-primary btn-sm">
                    <i class="fas fa-phone"></i> Telepon
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeMainImage(src) {
    document.querySelector('.card img').src = src;
}

document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const tipeDurasi = document.getElementById('tipe_durasi');
    const calculationDetails = document.getElementById('calculationDetails');
    const totalBiaya = document.getElementById('totalBiaya');
    const submitBtn = document.getElementById('submitBtn');

    const tarif = {
        harian: {{ $motor->tarifRental->tarif_harian ?? 0 }},
        mingguan: {{ $motor->tarifRental->tarif_mingguan ?? 0 }},
        bulanan: {{ $motor->tarifRental->tarif_bulanan ?? 0 }}
    };

    function hitungBiaya() {
        const mulai = new Date(tanggalMulai.value);
        const selesai = new Date(tanggalSelesai.value);
        const durasi = tipeDurasi.value;

        if (mulai && selesai && selesai > mulai && durasi && tarif[durasi] > 0) {
            const diffTime = Math.abs(selesai - mulai);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            const biaya = tarif[durasi] * diffDays;
            
            calculationDetails.innerHTML = `
                <p class="mb-1">${diffDays} hari x Rp ${tarif[durasi].toLocaleString('id-ID')}</p>
                <p class="mb-1">Tipe: ${durasi}</p>
            `;
            
            totalBiaya.textContent = `Rp ${biaya.toLocaleString('id-ID')}`;
            submitBtn.disabled = false;
        } else {
            calculationDetails.innerHTML = `<p class="mb-1">Pilih tanggal dan durasi untuk melihat biaya</p>`;
            totalBiaya.textContent = '-';
            submitBtn.disabled = true;
        }
    }

    tanggalMulai.addEventListener('change', function() {
        if (this.value) {
            const minDate = new Date(this.value);
            minDate.setDate(minDate.getDate() + 1);
            tanggalSelesai.min = minDate.toISOString().split('T')[0];
            
            if (tanggalSelesai.value && new Date(tanggalSelesai.value) <= new Date(this.value)) {
                tanggalSelesai.value = '';
            }
        }
        hitungBiaya();
    });

    tanggalSelesai.addEventListener('change', hitungBiaya);
    tipeDurasi.addEventListener('change', hitungBiaya);

    // Initial calculation
    hitungBiaya();
});
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}
.cursor-pointer:hover {
    opacity: 0.8;
}
.sticky-top {
    position: sticky;
    z-index: 100;
}
</style>
@endpush