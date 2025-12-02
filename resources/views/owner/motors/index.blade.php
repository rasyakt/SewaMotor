@extends('layouts.app')

@section('title', 'Daftar Motor Saya - Owner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-motorcycle"></i> Daftar Motor Saya</h2>
            <a href="{{ route('owner.motors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Motor
            </a>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Motor</h6>
                        <h3>{{ $motors->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-motorcycle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Terverifikasi</h6>
                        <h3>{{ $motors->where('status', 'verified')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Disewa</h6>
                        <h3>{{ $motors->where('status', 'disewa')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-road fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Menunggu</h6>
                        <h3>{{ $motors->where('status', 'pending')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @forelse($motors as $motor)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="position-relative">
              <img src="{{ asset('storage/' . $motor->photo) }}" class="card-img-top" 
                  alt="{{ $motor->merk }}" style="height: 200px; object-fit: cover;"
                  onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
                <span class="position-absolute top-0 start-0 m-2 badge 
                    @if($motor->status == 'verified') bg-success
                    @elseif($motor->status == 'pending') bg-warning
                    @elseif($motor->status == 'disewa') bg-info
                    @else bg-secondary @endif">
                    {{ $motor->status }}
                </span>
            </div>
            
            <div class="card-body">
                <h5 class="card-title">{{ $motor->merk }} - {{ $motor->tipe_cc }}CC</h5>
                <p class="card-text">
                    <strong>Plat:</strong> {{ $motor->no_plat }}<br>
                    <strong>Terdaftar:</strong> {{ $motor->created_at->format('d M Y') }}
                </p>
                
                @if($motor->tarifRental)
                <div class="mb-3">
                    <strong>Tarif Rental:</strong>
                    <div class="mt-1">
                        <span class="badge bg-light text-dark border">
                            <i class="fas fa-calendar-day text-primary"></i> 
                            Rp {{ number_format($motor->tarifRental->tarif_harian, 0, ',', '.') }}
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="fas fa-calendar-week text-success"></i> 
                            Rp {{ number_format($motor->tarifRental->tarif_mingguan, 0, ',', '.') }}
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="fas fa-calendar-alt text-info"></i> 
                            Rp {{ number_format($motor->tarifRental->tarif_bulanan, 0, ',', '.') }}
                        </span>
                    </div>
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
                    <span class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-clock"></i> Menunggu
                    </span>
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
                            <img src="{{ asset('storage/' . $motor->photo) }}" class="img-fluid rounded" alt="{{ $motor->merk }}" onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
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
                            <p><strong>Tanggal Daftar:</strong> {{ $motor->created_at->format('d M Y') }}</p>
                            
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