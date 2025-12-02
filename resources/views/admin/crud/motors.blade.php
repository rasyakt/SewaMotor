@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Data Motor</h2>
    <a href="{{ route('crud-motors.create') }}" class="btn btn-primary mb-2">Tambah Motor</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Merk</th>
                <th>Tipe CC</th>
                <th>No Plat</th>
                <th>Pemilik</th>
                <th>Status</th>
                <th>Tarif Harian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($motors as $motor)
            <tr>
                <td>{{ $motor->id }}</td>
                <td>{{ $motor->merk }}</td>
                <td>{{ $motor->tipe_cc }}</td>
                <td>{{ $motor->no_plat }}</td>
                <td>{{ $motor->pemilik ? $motor->pemilik->nama : '-' }}</td>
                <td>{{ $motor->status }}</td>
                <td>{{ $motor->tarifRental ? $motor->tarifRental->tarif_harian : '-' }}</td>
                <td>
                    <a href="{{ route('crud-motors.edit', $motor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('crud-motors.destroy', $motor->id) }}" method="POST" style="display:inline-block;">
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