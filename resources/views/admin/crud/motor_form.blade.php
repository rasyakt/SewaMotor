@extends('layouts.app')
@section('content')
<div class="container">
    <h2>{{ isset($motor) ? 'Edit Motor' : 'Tambah Motor' }}</h2>
    <form method="POST" action="{{ isset($motor) ? route('crud-motors.update', $motor->id) : route('crud-motors.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($motor))
            @method('PUT')
        @endif
        <input type="hidden" name="_method" value="{{ isset($motor) ? 'PUT' : 'POST' }}">
        <div class="mb-3">
            <label>Pemilik ID</label>
            <input type="number" name="pemilik_id" class="form-control" value="{{ old('pemilik_id', $motor->pemilik_id ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Merk</label>
            <input type="text" name="merk" class="form-control" value="{{ old('merk', $motor->merk ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Tipe CC</label>
            <select name="tipe_cc" class="form-select" required>
                <option value="">-- Pilih CC --</option>
                <option value="110" {{ old('tipe_cc', $motor->tipe_cc ?? '') == '110' ? 'selected' : '' }}>110 CC</option>
                <option value="125" {{ old('tipe_cc', $motor->tipe_cc ?? '') == '125' ? 'selected' : '' }}>125 CC</option>
                <option value="150" {{ old('tipe_cc', $motor->tipe_cc ?? '') == '150' ? 'selected' : '' }}>150 CC</option>
            </select>
        </div>
        <div class="mb-3">
            <label>No Plat</label>
            <input type="text" name="no_plat" class="form-control" value="{{ old('no_plat', $motor->no_plat ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select" required>
                <option value="">-- Pilih Status --</option>
                <option value="pending" {{ old('status', $motor->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ old('status', $motor->status ?? '') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="tersedia" {{ old('status', $motor->status ?? '') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="disewa" {{ old('status', $motor->status ?? '') == 'disewa' ? 'selected' : '' }}>Disewa</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Foto Motor</label>
            @if(isset($motor) && $motor->photo)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $motor->photo) }}" alt="Foto Motor" width="120">
                </div>
            @endif
            <input type="file" name="photo" class="form-control" accept="image/*">
            @if(isset($motor) && $motor->photo)
                <small>Biarkan kosong jika tidak ingin mengubah foto.</small>
            @endif
        </div>
        <div class="mb-3">
            <label>Dokumen Kepemilikan</label>
            @if(isset($motor) && $motor->dokumen_kepemilikan)
                <div class="mb-2">
                    <a href="{{ asset('storage/' . $motor->dokumen_kepemilikan) }}" target="_blank">Lihat Dokumen</a>
                </div>
            @endif
            <input type="file" name="dokumen_kepemilikan" class="form-control" accept="application/pdf,image/*">
            @if(isset($motor) && $motor->dokumen_kepemilikan)
                <small>Biarkan kosong jika tidak ingin mengubah dokumen.</small>
            @endif
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $motor->deskripsi ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">{{ isset($motor) ? 'Update' : 'Simpan' }}</button>
        <a href="{{ route('crud-motors.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection