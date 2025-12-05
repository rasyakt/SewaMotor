@extends('layouts.app')

@section('title', 'History Penyewaan - Customer')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-history"></i> History Penyewaan</h2>
        <p class="text-muted">Riwayat penyewaan motor Anda</p>
        <hr>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Filter Status</label>
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu Pembayaran</option>
                            <option value="dibayar">Menunggu Konfirmasi</option>
                            <option value="dikonfirmasi">Aktif</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sort By</label>
                        <select class="form-select" id="sortBy">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="price_high">Harga Tertinggi</option>
                            <option value="price_low">Harga Terendah</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button class="btn btn-outline-primary me-2" onclick="applyFilters()">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                        <button class="btn btn-outline-secondary" onclick="resetFilters()">
                            <i class="fas fa-refresh"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
                                <th>Motor</th>
                                <th>Tanggal Sewa</th>
                                <th>Durasi</th>
                                <th>Total Biaya</th>
                                <th>Status Penyewaan</th>
                                <th>Status Pengembalian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penyewaans as $penyewaan)
                            <tr class="booking-row" data-status="{{ $penyewaan->status }}" data-price="{{ $penyewaan->harga_total }}" data-date="{{ $penyewaan->created_at->timestamp }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $penyewaan->motor->photo) }}" 
                                             class="rounded me-3" 
                                             alt="{{ $penyewaan->motor->merk }}"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $penyewaan->motor->merk }}</strong><br>
                                            <small class="text-muted">{{ $penyewaan->motor->no_plat }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($penyewaan->tanggal_mulai)->format('d M Y') }}<br>
                                    <small>s/d {{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->format('d M Y') }}</small>
                                </td>
                                <td>
                                    {{ $penyewaan->hitungDurasi() }} hari<br>
                                    <small class="text-muted">{{ ucfirst($penyewaan->tipe_durasi) }}</small>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</strong>
                                    @if($penyewaan->transaksi)
                                    <br><small class="text-muted">
                                        {{ ucfirst($penyewaan->transaksi->metode_pembayaran) }}
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    @if($penyewaan->status == 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Menunggu Pembayaran
                                        </span>
                                    @elseif($penyewaan->status == 'dibayar')
                                        <span class="badge bg-info">
                                            <i class="fas fa-check-circle"></i> Menunggu Konfirmasi
                                        </span>
                                    @elseif($penyewaan->status == 'dikonfirmasi')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-play-circle"></i> Aktif
                                        </span>
                                    @elseif($penyewaan->status == 'selesai')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>
                                    @elseif($penyewaan->status == 'dibatalkan')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Dibatalkan
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $penyewaan->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($penyewaan->status == 'selesai')
                                        @if($penyewaan->status_pengembalian == 'tepat_waktu')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Tepat Waktu
                                            </span>
                                        @elseif($penyewaan->status_pengembalian == 'terlambat')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Terlambat {{ $penyewaan->hari_terlambat }} hari
                                            </span>
                                            @if($penyewaan->denda_keterlambatan > 0)
                                                <br><small class="text-danger">
                                                    Denda: Rp {{ number_format($penyewaan->denda_keterlambatan, 0, ',', '.') }}
                                                </small>
                                            @endif
                                        @elseif($penyewaan->status_pengembalian == 'rusak')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-tools"></i> Rusak
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-hourglass"></i> Belum Dikembalikan
                                            </span>
                                        @endif
                                    @elseif($penyewaan->isOverdue())
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-triangle"></i> OVERDUE!
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-hourglass"></i> Belum Dikembalikan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($penyewaan->status == 'pending')
                                        <a href="{{ route('customer.payments.create', $penyewaan->id) }}" 
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-credit-card"></i> Bayar
                                        </a>
                                    @elseif($penyewaan->status == 'dibayar')
                                        <span class="text-info">
                                            <i class="fas fa-clock"></i> Menunggu Admin
                                        </span>
                                    @elseif($penyewaan->status == 'dikonfirmasi')
                                        <button class="btn btn-warning btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#returnModal{{ $penyewaan->id }}">
                                            <i class="fas fa-undo"></i> Kembalikan
                                        </button>
                                    @endif
                                    
                                    <button class="btn btn-outline-primary btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal{{ $penyewaan->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>

                            <!-- Return Modal -->
                            <div class="modal fade" id="returnModal{{ $penyewaan->id }}" tabindex="-1">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title">
                                                <i class="fas fa-undo"></i> Kembalikan Motor
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('customer.bookings.return', $penyewaan->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <strong>{{ $penyewaan->motor->merk }} ({{ $penyewaan->motor->no_plat }})</strong><br>
                                                    Tanggal Selesai: {{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->format('d M Y') }}
                                                    @if(\Carbon\Carbon::now()->isAfter($penyewaan->tanggal_selesai))
                                                        <br><span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                            Terlambat {{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->diffInDays(\Carbon\Carbon::now()) }} hari!
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Kondisi Motor</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_motor" 
                                                               id="kondisi_baik{{ $penyewaan->id }}" value="baik" required>
                                                        <label class="form-check-label" for="kondisi_baik{{ $penyewaan->id }}">
                                                            <i class="fas fa-check-circle text-success"></i> Baik-Baik Saja
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="kondisi_motor" 
                                                               id="kondisi_rusak{{ $penyewaan->id }}" value="rusak">
                                                        <label class="form-check-label" for="kondisi_rusak{{ $penyewaan->id }}">
                                                            <i class="fas fa-exclamation-circle text-danger"></i> Ada yang Rusak
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="catatan{{ $penyewaan->id }}" class="form-label">Catatan (Opsional)</label>
                                                    <textarea class="form-control" id="catatan{{ $penyewaan->id }}" 
                                                              name="catatan" rows="3" 
                                                              placeholder="Tuliskan catatan atau kerusakan jika ada..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fas fa-undo"></i> Kembalikan Motor
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal{{ $penyewaan->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Penyewaan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 text-center">
                                                    <img src="{{ asset('storage/' . $penyewaan->motor->photo) }}" 
                                                         class="img-fluid rounded mb-3" 
                                                         alt="{{ $penyewaan->motor->merk }}"
                                                         style="max-height: 200px;">
                                                    <h5>{{ $penyewaan->motor->merk }}</h5>
                                                    <p class="text-muted">{{ $penyewaan->motor->no_plat }}</p>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <strong>Tanggal Mulai:</strong><br>
                                                            {{ \Carbon\Carbon::parse($penyewaan->tanggal_mulai)->format('d M Y') }}
                                                        </div>
                                                        <div class="col-6">
                                                            <strong>Tanggal Selesai:</strong><br>
                                                            {{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->format('d M Y') }}
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <strong>Durasi:</strong><br>
                                                            {{ $penyewaan->hitungDurasi() }} hari
                                                        </div>
                                                        <div class="col-6">
                                                            <strong>Tipe Durasi:</strong><br>
                                                            {{ ucfirst($penyewaan->tipe_durasi) }}
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <strong>Total Biaya:</strong><br>
                                                            <h4 class="text-success">Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</h4>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <strong>Status:</strong><br>
                                                            @if($penyewaan->status == 'pending')
                                                                <span class="badge bg-warning fs-6">Menunggu Pembayaran</span>
                                                            @elseif($penyewaan->status == 'dibayar')
                                                                <span class="badge bg-info fs-6">Menunggu Konfirmasi</span>
                                                            @elseif($penyewaan->status == 'dikonfirmasi')
                                                                <span class="badge bg-primary fs-6">Aktif</span>
                                                            @elseif($penyewaan->status == 'selesai')
                                                                <span class="badge bg-success fs-6">Selesai</span>
                                                            @else
                                                                <span class="badge bg-secondary fs-6">{{ $penyewaan->status }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    @if($penyewaan->transaksi)
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h6>Informasi Pembayaran:</h6>
                                                            <p><strong>Metode:</strong> {{ ucfirst($penyewaan->transaksi->metode_pembayaran) }}</p>
                                                            <p><strong>Status Pembayaran:</strong> 
                                                                @if($penyewaan->transaksi->status == 'sukses')
                                                                    <span class="badge bg-success">Sukses</span>
                                                                @elseif($penyewaan->transaksi->status == 'pending')
                                                                    <span class="badge bg-warning">Pending</span>
                                                                @else
                                                                    <span class="badge bg-danger">Gagal</span>
                                                                @endif
                                                            </p>
                                                            @if($penyewaan->transaksi->bukti_pembayaran)
                                                            <p>
                                                                <strong>Bukti Transfer:</strong><br>
                                                                <a href="{{ asset('storage/' . $penyewaan->transaksi->bukti_pembayaran) }}" 
                                                                   target="_blank" class="btn btn-sm btn-outline-info">
                                                                    <i class="fas fa-file-image"></i> Lihat Bukti
                                                                </a>
                                                            </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            @if($penyewaan->status == 'pending')
                                            <a href="{{ route('customer.payments.create', $penyewaan->id) }}" 
                                               class="btn btn-success">
                                                <i class="fas fa-credit-card"></i> Lanjutkan Pembayaran
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h4>Belum ada history penyewaan</h4>
                    <p class="text-muted">Mulai sewa motor pertama Anda</p>
                    <a href="{{ route('customer.motors') }}" class="btn btn-primary">
                        <i class="fas fa-motorcycle"></i> Sewa Motor Sekarang
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
function applyFilters() {
    const statusFilter = document.getElementById('filterStatus').value;
    const sortBy = document.getElementById('sortBy').value;
    const rows = document.querySelectorAll('.booking-row');
    
    let visibleRows = [];
    
    // Filter by status
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        
        if (!statusFilter || rowStatus === statusFilter) {
            row.style.display = '';
            visibleRows.push(row);
        } else {
            row.style.display = 'none';
        }
    });
    
    // Sort rows
    visibleRows.sort((a, b) => {
        switch(sortBy) {
            case 'newest':
                return parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date'));
            case 'oldest':
                return parseInt(a.getAttribute('data-date')) - parseInt(b.getAttribute('data-date'));
            case 'price_high':
                return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
            case 'price_low':
                return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
            default:
                return 0;
        }
    });
    
    // Reorder table
    const tbody = document.querySelector('tbody');
    visibleRows.forEach(row => {
        tbody.appendChild(row);
    });
}

function resetFilters() {
    document.getElementById('filterStatus').value = '';
    document.getElementById('sortBy').value = 'newest';
    applyFilters();
}

// Initialize filters
document.addEventListener('DOMContentLoaded', function() {
    applyFilters();
});
</script>
@endpush