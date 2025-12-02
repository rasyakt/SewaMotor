@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Data Pembayaran (Transaksi)</h2>
    <a href="{{ route('crud-transaksis.create') }}" class="btn btn-primary mb-2">Tambah Transaksi</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Penyewa</th>
                <th>Motor</th>
                <th>Jumlah</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $transaksi)
            <tr>
                <td>{{ $transaksi->id }}</td>
                <td>{{ $transaksi->penyewaan && $transaksi->penyewaan->penyewa ? $transaksi->penyewaan->penyewa->nama : '-' }}</td>
                <td>{{ $transaksi->penyewaan && $transaksi->penyewaan->motor ? $transaksi->penyewaan->motor->merk : '-' }}</td>
                <td>{{ $transaksi->jumlah }}</td>
                <td>{{ $transaksi->metode_pembayaran }}</td>
                <td>{{ $transaksi->status }}</td>
                <td>{{ $transaksi->tanggal }}</td>
                <td>
                    <a href="{{ route('crud-transaksis.edit', $transaksi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('crud-transaksis.destroy', $transaksi->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection