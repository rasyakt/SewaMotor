@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Data Tarif Rental</h2>
    <a href="{{ route('crud-tarif-rentals.create') }}" class="btn btn-primary mb-2">Tambah Tarif</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Motor</th>
                <th>Tarif Harian</th>
                <th>Tarif Mingguan</th>
                <th>Tarif Bulanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tarifRentals as $tarif)
            <tr>
                <td>{{ $tarif->id }}</td>
                <td>{{ $tarif->motor ? $tarif->motor->merk : '-' }}</td>
                <td>{{ $tarif->tarif_harian }}</td>
                <td>{{ $tarif->tarif_mingguan }}</td>
                <td>{{ $tarif->tarif_bulanan }}</td>
                <td>
                    <a href="{{ route('crud-tarif-rentals.edit', $tarif->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('crud-tarif-rentals.destroy', $tarif->id) }}" method="POST" style="display:inline-block;">
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