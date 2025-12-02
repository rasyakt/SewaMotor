@extends('layouts.app')
@section('content')
<div class="container">
    <h2>{{ isset($tarif) ? 'Edit Tarif Rental' : 'Tambah Tarif Rental' }}</h2>
    <form method="POST" action="{{ isset($tarif) ? route('crud-tarif-rentals.update', $tarif->id) : route('crud-tarif-rentals.store') }}">
        @csrf
        @if(isset($tarif))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label>Motor ID</label>
            <input type="number" name="motor_id" class="form-control" value="{{ old('motor_id', $tarif->motor_id ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Tarif Harian</label>
            <input type="number" name="tarif_harian" class="form-control" value="{{ old('tarif_harian', $tarif->tarif_harian ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Tarif Mingguan</label>
            <input type="number" name="tarif_mingguan" class="form-control" value="{{ old('tarif_mingguan', $tarif->tarif_mingguan ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Tarif Bulanan</label>
            <input type="number" name="tarif_bulanan" class="form-control" value="{{ old('tarif_bulanan', $tarif->tarif_bulanan ?? '') }}" required>
        </div>
        <button type="submit" class="btn btn-success">{{ isset($tarif) ? 'Update' : 'Simpan' }}</button>
        <a href="{{ route('crud-tarif-rentals.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection