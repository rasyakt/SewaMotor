@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Data Penyewaan</h2>
    <a href="{{ route('crud-penyewaans.create') }}" class="btn btn-primary mb-2">Tambah Penyewaan</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Penyewa</th>
                <th>Motor</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Tipe Durasi</th>
                <th>Harga Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penyewaans as $penyewaan)
            <tr>
                <td>{{ $penyewaan->id }}</td>
                <td>{{ $penyewaan->penyewa ? $penyewaan->penyewa->nama : '-' }}</td>
                <td>{{ $penyewaan->motor ? $penyewaan->motor->merk : '-' }}</td>
                <td>{{ $penyewaan->tanggal_mulai }}</td>
                <td>{{ $penyewaan->tanggal_selesai }}</td>
                <td>{{ $penyewaan->tipe_durasi }}</td>
                <td>{{ $penyewaan->harga_total }}</td>
                <td>{{ $penyewaan->status }}</td>
                <td>
                    <a href="{{ route('crud-penyewaans.edit', $penyewaan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('crud-penyewaans.destroy', $penyewaan->id) }}" method="POST" style="display:inline-block;">
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