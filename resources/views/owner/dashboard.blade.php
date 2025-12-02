@extends('layouts.app')

@section('title', 'Dashboard - Owner')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard Pemilik</h2>
        <p class="text-muted">Ringkasan performa motor dan pendapatan Anda</p>
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
                            Total Motor</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_motor'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-motorcycle fa-2x text-gray-300"></i>
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
                            Motor Terverifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['motor_verified'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            Motor Disewa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['motor_disewa'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-road fa-2x text-gray-300"></i>
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
                            Total Pendapatan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}
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
                        <a href="{{ route('owner.motors.create') }}" class="btn btn-primary w-100 h-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                            Tambah Motor Baru
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('owner.motors') }}" class="btn btn-success w-100 h-100 py-3">
                            <i class="fas fa-list fa-2x mb-2"></i><br>
                            Kelola Motor Saya
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('owner.revenue') }}" class="btn btn-info w-100 h-100 py-3">
                            <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                            Lihat Laporan Pendapatan
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('customer.motors') }}" class="btn btn-warning w-100 h-100 py-3">
                            <i class="fas fa-search fa-2x mb-2"></i><br>
                            Lihat Motor Tersedia
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-trophy"></i> Performance</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $performance = $stats['total_motor'] > 0 ? 
                        round(($stats['motor_verified'] / $stats['total_motor']) * 100) : 0;
                @endphp
                <div class="mb-3">
                    <div class="fs-1 fw-bold text-success">{{ $performance }}%</div>
                    <div class="text-muted">Rate Verifikasi</div>
                </div>
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $performance }}%" 
                         aria-valuenow="{{ $performance }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <p class="small text-muted mb-0">
                    {{ $stats['motor_verified'] }} dari {{ $stats['total_motor'] }} motor terverifikasi
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                @php
                    $recentActivities = \App\Models\Penyewaan::with(['motor', 'penyewa'])
                        ->whereHas('motor', function($query) {
                            $query->where('pemilik_id', auth()->id());
                        })
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($recentActivities->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentActivities as $activity)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $activity->motor->merk }}</h6>
                            <small class="text-muted">
                                Disewa oleh {{ $activity->penyewa->nama }} • 
                                {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <span class="badge bg-{{ $activity->status == 'dikonfirmasi' ? 'success' : 'warning' }}">
                            {{ $activity->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Belum ada aktivitas terbaru</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Status Motor</h5>
            </div>
            <div class="card-body">
                <canvas id="motorStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Motor List Preview -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-motorcycle"></i> Motor Saya</h5>
                <a href="{{ route('owner.motors') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @php
                    $userMotors = auth()->user()->motors()->with('tarifRental')->limit(3)->get();
                @endphp
                
                @if($userMotors->count() > 0)
                <div class="row">
                    @foreach($userMotors as $motor)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                       <img src="{{ asset('storage/' . $motor->photo) }}" class="card-img-top" 
                           onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';" 
                                 alt="{{ $motor->merk }}" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">{{ $motor->merk }}</h6>
                                <p class="card-text small">
                                    <span class="badge bg-{{ $motor->status == 'verified' ? 'success' : 'warning' }}">
                                        {{ $motor->status }}
                                    </span><br>
                                    {{ $motor->no_plat }}
                                </p>
                                @if($motor->tarifRental)
                                <p class="card-text small text-success">
                                    Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}/hari
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-motorcycle fa-3x text-muted mb-3"></i>
                    <h5>Belum ada motor</h5>
                    <p class="text-muted">Mulai dengan menambahkan motor pertama Anda</p>
                    <a href="{{ route('owner.motors.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Motor
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Motor Status Chart
    const ctx = document.getElementById('motorStatusChart').getContext('2d');
    const motorStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Terverifikasi', 'Menunggu Verifikasi', 'Disewa'],
            datasets: [{
                data: [
                    {{ $stats['motor_verified'] }},
                    {{ $stats['total_motor'] - $stats['motor_verified'] - $stats['motor_disewa'] }},
                    {{ $stats['motor_disewa'] }}
                ],
                backgroundColor: [
                    '#1cc88a',
                    '#f6c23e',
                    '#36b9cc'
                ],
                hoverBackgroundColor: [
                    '#17a673',
                    '#dda20a',
                    '#2c9faf'
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
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush