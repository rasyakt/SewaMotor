@extends('layouts.app')

@section('title', 'Dashboard - Customer')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard Penyewa</h2>
        <p class="text-muted">Selamat datang di dashboard penyewaan motor Anda</p>
        <hr>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Penyewaan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_penyewaan'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Penyewaan Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['penyewaan_aktif'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-play-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Penyewaan Selesai</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['penyewaan_selesai'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Pengeluaran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($stats['total_pengeluaran'] ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('customer.motors') }}" class="btn btn-primary w-100 h-100 py-3">
                            <i class="fas fa-motorcycle fa-2x mb-2"></i><br>
                            Sewa Motor Baru
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('customer.bookings.history') }}" class="btn btn-success w-100 h-100 py-3">
                            <i class="fas fa-history fa-2x mb-2"></i><br>
                            Lihat History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Bookings -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-road"></i> Penyewaan Aktif</h5>
                <a href="{{ route('customer.bookings.history') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @php
                    $activeBookings = auth()->user()->penyewaans()
                        ->with(['motor', 'transaksi'])
                        ->whereIn('status', ['dikonfirmasi', 'dibayar'])
                        ->orderBy('tanggal_mulai', 'desc')
                        ->limit(3)
                        ->get();
                @endphp
                
                @if($activeBookings->count() > 0)
                <div class="row">
                    @foreach($activeBookings as $booking)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-{{ $booking->status == 'dikonfirmasi' ? 'success' : 'info' }}">
                       <img src="{{ asset('storage/' . $booking->motor->photo) }}" class="card-img-top" 
                           onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';" 
                                 alt="{{ $booking->motor->merk }}" style="height: 120px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">{{ $booking->motor->merk }}</h6>
                                <p class="card-text small">
                                    <strong>Periode:</strong><br>
                                    {{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M') }} - 
                                    {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}
                                </p>
                                <p class="card-text">
                                    <span class="badge bg-{{ $booking->status == 'dikonfirmasi' ? 'success' : 'info' }}">
                                        {{ $booking->status }}
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('customer.bookings.history') }}" class="btn btn-sm btn-outline-primary w-100">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-road fa-3x text-muted mb-3"></i>
                    <h5>Belum ada penyewaan aktif</h5>
                    <p class="text-muted">Mulai sewa motor pertama Anda</p>
                    <a href="{{ route('customer.motors') }}" class="btn btn-primary">
                        <i class="fas fa-motorcycle"></i> Sewa Motor Sekarang
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Profile Summary -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Profil Saya</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-4x text-primary"></i>
                </div>
                <h5>{{ auth()->user()->nama }}</h5>
                <p class="text-muted mb-1">{{ auth()->user()->email }}</p>
                <p class="text-muted mb-3">{{ auth()->user()->no_tlpn }}</p>
                <div class="d-grid">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fas fa-edit"></i> Edit Profil
                    </button>
                </div>
            </div>
        </div>

        <!-- Recommended Motors -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-star"></i> Motor Rekomendasi</h5>
            </div>
            <div class="card-body">
                @php
                    $recommendedMotors = \App\Models\Motor::with('tarifRental')
                        ->where('status', 'tersedia')
                        ->inRandomOrder()
                        ->limit(2)
                        ->get();
                @endphp
                
                @if($recommendedMotors->count() > 0)
                <div class="row g-3">
                    @foreach($recommendedMotors as $motor)
                    <div class="col-12">
                        <div class="card h-100">
                       <img src="{{ asset('storage/' . $motor->photo) }}" class="card-img-top" 
                           onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';" 
                                 alt="{{ $motor->merk }}" style="height: 100px; object-fit: cover;">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1">{{ $motor->merk }}</h6>
                                <p class="card-text small mb-1">
                                    {{ $motor->tipe_cc }} CC • {{ $motor->no_plat }}
                                </p>
                                @if($motor->tarifRental)
                                <p class="card-text small text-success mb-0">
                                    Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}/hari
                                </p>
                                @endif
                            </div>
                            <div class="card-footer p-2 bg-transparent">
                                <a href="{{ route('customer.motors.show', $motor->id) }}" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-3">
                    <i class="fas fa-motorcycle fa-2x text-muted mb-2"></i>
                    <p class="text-muted small mb-0">Tidak ada motor tersedia</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Statistik Penyewaan</h5>
            </div>
            <div class="card-body">
                <canvas id="bookingStatsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Aktivitas Terbaru</h5>
                <a href="{{ route('customer.bookings.history') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @php
                    $recentActivities = auth()->user()->penyewaans()
                        ->with(['motor'])
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($recentActivities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Motor</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $activity)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $activity->motor->photo) }}" 
                                    onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';" 
                                             class="rounded me-2" 
                                             alt="{{ $activity->motor->merk }}"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $activity->motor->merk }}</strong><br>
                                            <small class="text-muted">{{ $activity->motor->no_plat }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($activity->tanggal_mulai)->format('d M') }}<br>
                                    <small>s/d {{ \Carbon\Carbon::parse($activity->tanggal_selesai)->format('d M') }}</small>
                                </td>
                                <td>{{ $activity->hitungDurasi() }} hari</td>
                                <td>Rp {{ number_format($activity->harga_total, 0, ',', '.') }}</td>
                                <td>
                                    @if($activity->status == 'pending')
                                        <span class="badge bg-warning">Menunggu Bayar</span>
                                    @elseif($activity->status == 'dibayar')
                                        <span class="badge bg-info">Menunggu Konfirmasi</span>
                                    @elseif($activity->status == 'dikonfirmasi')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($activity->status == 'selesai')
                                        <span class="badge bg-secondary">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">{{ $activity->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('customer.bookings.history') }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5>Belum ada aktivitas</h5>
                    <p class="text-muted">Mulai dengan menyewa motor pertama Anda</p>
                    <a href="{{ route('customer.motors') }}" class="btn btn-primary">
                        <i class="fas fa-motorcycle"></i> Sewa Motor
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="profileForm">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->no_tlpn }}" readonly>
                    </div>
                </form>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Untuk mengubah data profil, silakan hubungi administrator.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Booking Stats Chart
    const ctx = document.getElementById('bookingStatsChart').getContext('2d');
    const bookingStatsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Selesai', 'Menunggu', 'Lainnya'],
            datasets: [{
                data: [
                    {{ $stats['penyewaan_aktif'] }},
                    {{ $stats['penyewaan_selesai'] }},
                    {{ $stats['total_penyewaan'] - $stats['penyewaan_aktif'] - $stats['penyewaan_selesai'] }},
                    0
                ],
                backgroundColor: [
                    '#1cc88a',
                    '#36b9cc', 
                    '#f6c23e',
                    '#e74a3b'
                ],
                hoverBackgroundColor: [
                    '#17a673',
                    '#2c9faf',
                    '#dda20a',
                    '#be2617'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Auto refresh active bookings every 30 seconds
    setInterval(function() {
        // You can implement auto-refresh here if needed
    }, 30000);
});
</script>
@endpush

<style>
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
</style>