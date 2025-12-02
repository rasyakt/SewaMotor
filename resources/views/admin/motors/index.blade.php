@extends('layouts.app')

@section('title', 'Daftar Motor Saya - Owner')

@section('content')
<div class="row">
<form method="GET" class="mb-4">
    <div class="row g-2">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="disewa" {{ request('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="merk" class="form-control" placeholder="Merk" value="{{ request('merk') }}">
        </div>
        <div class="col-md-3">
            <input type="number" name="pemilik_id" class="form-control" placeholder="ID Pemilik" value="{{ request('pemilik_id') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filter</button>
        </div>
    </div>
</form>
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-motorcycle"></i> Daftar Motor Saya</h2>
            {{-- Admin view: no add motor button here --}}
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
</div>

<div class="row">
    @forelse($motors as $motor)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
          <img src="{{ asset('storage/' . $motor->photo) }}" class="card-img-top" 
              alt="{{ $motor->merk }}" style="height: 200px; object-fit: cover;"
              onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
            
            <div class="card-body">
                <h5 class="card-title">{{ $motor->merk }} - {{ $motor->tipe_cc }}CC</h5>
                <p class="card-text">
                    <strong>Plat:</strong> {{ $motor->no_plat }}<br>
                    <strong>Status:</strong> 
                    @if($motor->status == 'pending')
                        <span class="badge bg-warning">Menunggu Verifikasi</span>
                    @elseif($motor->status == 'verified')
                        <span class="badge bg-success">Terverifikasi</span>
                    @elseif($motor->status == 'disewa')
                        <span class="badge bg-info">Disewa</span>
                    @elseif($motor->status == 'tersedia')
                        <span class="badge bg-primary">Tersedia</span>
                    @else
                        <span class="badge bg-secondary">{{ $motor->status }}</span>
                    @endif
                </p>
                
                @if($motor->tarifRental)
                <div class="mb-2">
                    <strong>Tarif Rental:</strong><br>
                    <small class="text-success">
                        <i class="fas fa-calendar-day"></i> Harian: Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}<br>
                        <i class="fas fa-calendar-week"></i> Mingguan: Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}<br>
                        <i class="fas fa-calendar-alt"></i> Bulanan: Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}
                    </small>
                </div>
                @endif

                @if($motor->deskripsi)
                <p class="card-text">
                    <small class="text-muted">{{ Str::limit($motor->deskripsi, 100) }}</small>
                </p>
                @endif
            </div>
            
            <div class="card-footer">
                <div class="btn-group w-100">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" 
                       data-bs-target="#detailModal{{ $motor->id }}">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    
                    @if($motor->status == 'pending')
                    <form action="{{ route('admin.motors.verify', $motor->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Verifikasi motor ini?')">
                            <i class="fas fa-check"></i> Verifikasi
                        </button>
                    </form>
                    @elseif($motor->status == 'verified')
                    <span class="btn btn-outline-success btn-sm">
                        <i class="fas fa-check"></i> Aktif
                    </span>
                    @elseif($motor->status == 'disewa')
                    <span class="btn btn-outline-info btn-sm">
                        <i class="fas fa-road"></i> Disewa
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal{{ $motor->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Motor - {{ $motor->merk }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ asset('storage/' . $motor->photo) }}" class="img-fluid rounded" alt="{{ $motor->merk }}">
                            @if($motor->dokumen_kepemilikan)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $motor->dokumen_kepemilikan) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-file-pdf"></i> Lihat Dokumen
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Motor:</h6>
                            <p><strong>Merk:</strong> {{ $motor->merk }}</p>
                            <p><strong>Tipe CC:</strong> {{ $motor->tipe_cc }} CC</p>
                            <p><strong>Plat:</strong> {{ $motor->no_plat }}</p>
                            <p><strong>Status:</strong> 
                                @if($motor->status == 'pending')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @elseif($motor->status == 'verified')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($motor->status == 'disewa')
                                    <span class="badge bg-info">Disewa</span>
                                @else
                                    <span class="badge bg-secondary">{{ $motor->status }}</span>
                                @endif
                            </p>
                            
                            @if($motor->tarifRental)
                            <h6 class="mt-3">Tarif Rental:</h6>
                            <p><strong>Harian:</strong> Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}</p>
                            <p><strong>Mingguan:</strong> Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}</p>
                            <p><strong>Bulanan:</strong> Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($motor->deskripsi)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Deskripsi:</h6>
                            <p>{{ $motor->deskripsi }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-motorcycle fa-3x mb-3 text-muted"></i>
            <h4>Belum ada motor yang terdaftar</h4>
            <p class="text-muted">Mulai dengan menambahkan motor pertama Anda.</p>
            <a href="{{ route('owner.motors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Motor Pertama
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($motors->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            {{ $motors->links() }}
        </div>
    </div>
</div>
@endif
@endsection