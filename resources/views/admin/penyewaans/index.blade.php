@extends('layouts.app')

@section('title', 'Manajemen Penyewaan - Admin')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-list"></i> Manajemen Penyewaan</h2>
        <p class="text-muted">Kelola semua transaksi penyewaan motor</p>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($penyewaans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Penyewa</th>
                                <th>Motor</th>
                                <th>Tanggal Sewa</th>
                                <th>Durasi</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penyewaans as $penyewaan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $penyewaan->penyewa->nama }}</strong><br>
                                    <small class="text-muted">{{ $penyewaan->penyewa->email }}</small>
                                </td>
                                <td>
                                    {{ $penyewaan->motor->merk }}<br>
                                    <small class="text-muted">{{ $penyewaan->motor->no_plat }}</small>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($penyewaan->tanggal_mulai)->format('d M Y') }}<br>
                                    <small>s/d {{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->format('d M Y') }}</small>
                                </td>
                                <td>
                                    {{ $penyewaan->hitungDurasi() }} hari<br>
                                    <small class="text-muted">{{ ucfirst($penyewaan->tipe_durasi) }}</small>
                                </td>
                                <td>Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</td>
                                <td>
                                    @if($penyewaan->status == 'pending')
                                        <span class="badge bg-warning">Menunggu Bayar</span>
                                    @elseif($penyewaan->status == 'dibayar')
                                        <span class="badge bg-info">Menunggu Konfirmasi</span>
                                    @elseif($penyewaan->status == 'dikonfirmasi')
                                        <span class="badge bg-primary">Aktif</span>
                                    @elseif($penyewaan->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $penyewaan->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $needsConfirmation = false;
                                        if ($penyewaan->status == 'dibayar') {
                                            $needsConfirmation = true;
                                        }
                                        // Also show confirm button for cash payments that are pending admin confirmation
                                        if ($penyewaan->transaksi && $penyewaan->transaksi->metode_pembayaran == 'cash' && $penyewaan->transaksi->status == 'pending') {
                                            $needsConfirmation = true;
                                        }
                                    @endphp

                                    @if($needsConfirmation)
                                        <form action="{{ route('admin.penyewaans.confirm', $penyewaan->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Konfirmasi
                                            </button>
                                        </form>
                                    @elseif($penyewaan->status == 'dikonfirmasi')
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Dikonfirmasi
                                        </span>
                                    @else
                                        <button class="btn btn-outline-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $penyewaan->id }}">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                    <h4>Belum ada penyewaan</h4>
                    <p class="text-muted">Data penyewaan akan muncul ketika customer melakukan booking.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection