@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Detail Transaksi</h2>
    <table class="table">
        <tr><th>ID</th><td>{{ $transaksi->id }}</td></tr>
        <tr><th>Penyewaan ID</th><td>{{ $transaksi->penyewaan_id }}</td></tr>
        <tr><th>Jumlah</th><td>{{ $transaksi->jumlah }}</td></tr>
        <tr><th>Metode Pembayaran</th><td>{{ $transaksi->metode_pembayaran }}</td></tr>
        <tr><th>Status</th><td>{{ $transaksi->status }}</td></tr>
        <tr><th>Tanggal</th><td>{{ $transaksi->tanggal }}</td></tr>
        <tr><th>Bukti Pembayaran</th><td><a href="{{ $transaksi->bukti_pembayaran }}" target="_blank">Lihat Bukti</a></td></tr>
    </table>
    <a href="{{ route('crud-transaksis.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection