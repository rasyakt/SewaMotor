@extends('layouts.app')

@section('title', 'Laporan Pendapatan - Owner')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-chart-line"></i> Laporan Pendapatan</h2>
        <p class="text-muted">Ringkasan pendapatan dari penyewaan motor Anda</p>
        <hr>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Pendapatan</h6>
                        <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Transaksi</h6>
                        <h3>{{ $bagiHasil->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-receipt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Rata-rata/Transaksi</h6>
                        <h3>Rp {{ number_format($bagiHasil->avg('bagi_hasil_pemilik') ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table"></i> Detail Pendapatan</h5>
            </div>
            <div class="card-body">
                @if($bagiHasil->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Motor</th>
                                <th>Periode Sewa</th>
                                <th>Pendapatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bagiHasil as $bagi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($bagi->tanggal)->format('d M Y') }}</td>
                                <td>
                                    <strong>{{ $bagi->penyewaan->motor->merk }}</strong><br>
                                    <small class="text-muted">{{ $bagi->penyewaan->motor->no_plat }}</small>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($bagi->penyewaan->tanggal_mulai)->format('d M Y') }}<br>
                                    <small>s/d {{ \Carbon\Carbon::parse($bagi->penyewaan->tanggal_selesai)->format('d M Y') }}</small>
                                </td>
                                <td class="text-success">
                                    <strong>Rp {{ number_format($bagi->bagi_hasil_pemilik, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($bagi->settled_at)
                                        <span class="badge bg-success">Settled</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h4>Belum ada data pendapatan</h4>
                    <p class="text-muted">Pendapatan akan muncul ketika motor Anda disewa.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection