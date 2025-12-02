@extends('layouts.app')

@section('title', 'Daftar Motor Tersedia - Customer')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-motorcycle"></i> Daftar Motor Tersedia</h2>
        <p class="text-muted">Pilih motor yang ingin Anda sewa</p>
        <hr>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6><i class="fas fa-filter"></i> Filter Motor</h6>
                <div class="mb-3">
                    <label class="form-label">Tipe CC</label>
                    <select class="form-select" id="filterCC">
                        <option value="">Semua Tipe</option>
                        <option value="100">100 CC</option>
                        <option value="125">125 CC</option>
                        <option value="150">150 CC</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rentang Harga</label>
                    <select class="form-select" id="filterHarga">
                        <option value="">Semua Harga</option>
                        <option value="0-50000">≤ Rp 50.000/hari</option>
                        <option value="50000-100000">Rp 50.000 - 100.000/hari</option>
                        <option value="100000-999999999">≥ Rp 100.000/hari</option>
                    </select>
                </div>
                <button class="btn btn-outline-primary btn-sm w-100" onclick="resetFilter()">
                    <i class="fas fa-refresh"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="row" id="motorList">
            @forelse($motors as $motor)
            <div class="col-md-6 mb-4 motor-item" data-cc="{{ $motor->tipe_cc }}" data-harga="{{ $motor->tarifRental->tarif_harian ?? 0 }}">
                <div class="card h-100 shadow-sm">
                <img src="{{ asset('storage/' . $motor->photo) }}" class="card-img-top" alt="{{ $motor->merk }}" 
                    onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $motor->merk }} - {{ $motor->tipe_cc }}CC</h5>
                        <p class="card-text">
                            <strong>Plat:</strong> {{ $motor->no_plat }}<br>
                            <strong>Status:</strong> 
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> {{ ucfirst($motor->status) }}
                            </span>
                        </p>
                        
                        @if($motor->tarifRental)
                        <div class="mb-3">
                            <strong>Tarif Harian:</strong><br>
                            <h5 class="text-success mb-1">Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}/hari</h5>
                            <small class="text-muted">
                                Mingguan: Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }} | 
                                Bulanan: Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}
                            </small>
                        </div>
                        @endif

                        @if($motor->deskripsi)
                        <p class="card-text">
                            <small class="text-muted">{{ Str::limit($motor->deskripsi, 80) }}</small>
                        </p>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid">
                            <a href="{{ route('customer.motors.show', $motor->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Detail & Sewa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>Tidak ada motor tersedia saat ini</h4>
                    <p class="text-muted">Silakan coba lagi nanti atau hubungi admin.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterMotors() {
    const selectedCC = document.getElementById('filterCC').value;
    const selectedHarga = document.getElementById('filterHarga').value;
    const motorItems = document.querySelectorAll('.motor-item');
    
    let visibleCount = 0;
    
    motorItems.forEach(item => {
        const cc = item.getAttribute('data-cc');
        const harga = parseInt(item.getAttribute('data-harga'));
        let show = true;
        
        // Filter by CC
        if (selectedCC && cc !== selectedCC) {
            show = false;
        }
        
        // Filter by Harga
        if (selectedHarga) {
            const [min, max] = selectedHarga.split('-').map(Number);
            if (harga < min || harga > max) {
                show = false;
            }
        }
        
        if (show) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Show message if no results
    const noResults = document.getElementById('noResults');
    if (visibleCount === 0) {
        if (!noResults) {
            const motorList = document.getElementById('motorList');
            motorList.innerHTML = `
                <div class="col-12" id="noResults">
                    <div class="alert alert-warning text-center py-5">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h4>Tidak ada motor yang sesuai dengan filter</h4>
                        <p class="text-muted">Coba ubah kriteria filter Anda.</p>
                    </div>
                </div>
            `;
        }
    } else if (noResults) {
        noResults.remove();
    }
}

function resetFilter() {
    document.getElementById('filterCC').value = '';
    document.getElementById('filterHarga').value = '';
    filterMotors();
}

// Event listeners
document.getElementById('filterCC').addEventListener('change', filterMotors);
document.getElementById('filterHarga').addEventListener('change', filterMotors);

// Initial filter
document.addEventListener('DOMContentLoaded', filterMotors);
</script>
@endpush