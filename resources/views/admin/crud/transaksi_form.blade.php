@extends('layouts.app')
@section('content')
<div class="container">
    <h2>{{ isset($transaksi) ? 'Edit Transaksi' : 'Tambah Transaksi' }}</h2>
    <form method="POST" action="{{ isset($transaksi) ? route('crud-transaksis.update', $transaksi->id) : route('crud-transaksis.store') }}">
        @csrf
        @if(isset($transaksi))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label>Penyewaan ID</label>
            <input type="number" name="penyewaan_id" class="form-control" value="{{ old('penyewaan_id', $transaksi->penyewaan_id ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $transaksi->jumlah ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Metode Pembayaran</label>
            <input type="text" name="metode_pembayaran" class="form-control" value="{{ old('metode_pembayaran', $transaksi->metode_pembayaran ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <input type="text" name="status" class="form-control" value="{{ old('status', $transaksi->status ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', isset($transaksi) ? $transaksi->tanggal->format('Y-m-d') : '') }}" required>
        </div>
        <div class="mb-3">
            <label>Bukti Pembayaran (URL)</label>
            <input type="text" name="bukti_pembayaran" class="form-control" value="{{ old('bukti_pembayaran', $transaksi->bukti_pembayaran ?? '') }}">
        </div>
        <button type="submit" class="btn btn-success">{{ isset($transaksi) ? 'Update' : 'Simpan' }}</button>
        <a href="{{ route('crud-transaksis.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection