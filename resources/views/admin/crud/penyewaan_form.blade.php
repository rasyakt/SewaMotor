@extends('layouts.app')
@section('content')
<div class="container">
    <h2>{{ isset($penyewaan) ? 'Edit Penyewaan' : 'Tambah Penyewaan' }}</h2>
    <form method="POST" action="{{ isset($penyewaan) ? route('crud-penyewaans.update', $penyewaan->id) : route('crud-penyewaans.store') }}">
        @csrf
        @if(isset($penyewaan))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label>Penyewa ID</label>
            <input type="number" name="penyewa_id" class="form-control" value="{{ old('penyewa_id', $penyewaan->penyewa_id ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Motor ID</label>
            <input type="number" name="motor_id" class="form-control" value="{{ old('motor_id', $penyewaan->motor_id ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', isset($penyewaan) ? $penyewaan->tanggal_mulai->format('Y-m-d') : '') }}" required>
        </div>
        <div class="mb-3">
            <label>Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', isset($penyewaan) ? $penyewaan->tanggal_selesai->format('Y-m-d') : '') }}" required>
        </div>
        <div class="mb-3">
            <label>Tipe Durasi</label>
            <select name="tipe_durasi" class="form-control" required>
                <option value="harian" {{ old('tipe_durasi', $penyewaan->tipe_durasi ?? '') == 'harian' ? 'selected' : '' }}>Harian</option>
                <option value="mingguan" {{ old('tipe_durasi', $penyewaan->tipe_durasi ?? '') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ old('tipe_durasi', $penyewaan->tipe_durasi ?? '') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Harga Total</label>
            <input type="number" name="harga_total" class="form-control" value="{{ old('harga_total', $penyewaan->harga_total ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <input type="text" name="status" class="form-control" value="{{ old('status', $penyewaan->status ?? '') }}" required>
        </div>
        <button type="submit" class="btn btn-success">{{ isset($penyewaan) ? 'Update' : 'Simpan' }}</button>
        <a href="{{ route('crud-penyewaans.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection