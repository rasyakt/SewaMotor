@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Detail Penyewaan</h2>
    <table class="table">
        <tr><th>ID</th><td>{{ $penyewaan->id }}</td></tr>
        <tr><th>Penyewa ID</th><td>{{ $penyewaan->penyewa_id }}</td></tr>
        <tr><th>Motor ID</th><td>{{ $penyewaan->motor_id }}</td></tr>
        <tr><th>Tanggal Mulai</th><td>{{ $penyewaan->tanggal_mulai }}</td></tr>
        <tr><th>Tanggal Selesai</th><td>{{ $penyewaan->tanggal_selesai }}</td></tr>
        <tr><th>Tipe Durasi</th><td>{{ $penyewaan->tipe_durasi }}</td></tr>
        <tr><th>Harga Total</th><td>{{ $penyewaan->harga_total }}</td></tr>
        <tr><th>Status</th><td>{{ $penyewaan->status }}</td></tr>
    </table>
    <a href="{{ route('crud-penyewaans.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection