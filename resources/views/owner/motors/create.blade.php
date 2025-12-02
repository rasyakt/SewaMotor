@extends('layouts.app')

@section('title', 'Tambah Motor Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Tambah Motor Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.motors.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="merk" class="form-label">Merk Motor</label>
                                <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                                       id="merk" name="merk" value="{{ old('merk') }}" required>
                                @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipe_cc" class="form-label">Tipe CC</label>
                                <select class="form-control @error('tipe_cc') is-invalid @enderror" 
                                        id="tipe_cc" name="tipe_cc" required>
                                    <option value="">Pilih Tipe CC</option>
                                    <option value="100" {{ old('tipe_cc') == '100' ? 'selected' : '' }}>100 CC</option>
                                    <option value="125" {{ old('tipe_cc') == '125' ? 'selected' : '' }}>125 CC</option>
                                    <option value="150" {{ old('tipe_cc') == '150' ? 'selected' : '' }}>150 CC</option>
                                </select>
                                @error('tipe_cc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="no_plat" class="form-label">Nomor Plat</label>
                        <input type="text" class="form-control @error('no_plat') is-invalid @enderror" 
                               id="no_plat" name="no_plat" value="{{ old('no_plat') }}" required>
                        @error('no_plat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Foto Motor</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*" required>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format: JPEG, PNG, JPG (Max: 2MB)</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dokumen_kepemilikan" class="form-label">Dokumen Kepemilikan</label>
                                <input type="file" class="form-control @error('dokumen_kepemilikan') is-invalid @enderror" 
                                       id="dokumen_kepemilikan" name="dokumen_kepemilikan" accept="image/*,.pdf" required>
                                @error('dokumen_kepemilikan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format: Foto/PDF (Max: 2MB)</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Motor</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5>Tarif Rental</h5>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tarif_harian" class="form-label">Tarif Harian (Rp)</label>
                                <input type="number" class="form-control @error('tarif_harian') is-invalid @enderror" 
                                       id="tarif_harian" name="tarif_harian" value="{{ old('tarif_harian') }}" 
                                       min="0" required>
                                @error('tarif_harian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tarif_mingguan" class="form-label">Tarif Mingguan (Rp)</label>
                                <input type="number" class="form-control @error('tarif_mingguan') is-invalid @enderror" 
                                       id="tarif_mingguan" name="tarif_mingguan" value="{{ old('tarif_mingguan') }}" 
                                       min="0" required>
                                @error('tarif_mingguan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tarif_bulanan" class="form-label">Tarif Bulanan (Rp)</label>
                                <input type="number" class="form-control @error('tarif_bulanan') is-invalid @enderror" 
                                       id="tarif_bulanan" name="tarif_bulanan" value="{{ old('tarif_bulanan') }}" 
                                       min="0" required>
                                @error('tarif_bulanan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Motor
                        </button>
                        <a href="{{ route('owner.motors') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection