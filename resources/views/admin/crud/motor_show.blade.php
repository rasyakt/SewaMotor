@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Detail Motor</h2>
    <table class="table">
        <tr><th>ID</th><td>{{ $motor->id }}</td></tr>
        <tr><th>Pemilik ID</th><td>{{ $motor->pemilik_id }}</td></tr>
        <tr><th>Merk</th><td>{{ $motor->merk }}</td></tr>
        <tr><th>Tipe CC</th><td>{{ $motor->tipe_cc }}</td></tr>
        <tr><th>No Plat</th><td>{{ $motor->no_plat }}</td></tr>
        <tr><th>Status</th><td>{{ $motor->status }}</td></tr>
        <tr><th>Photo</th><td><img src="{{ $motor->photo }}" alt="Photo" width="120"></td></tr>
        <tr><th>Dokumen Kepemilikan</th><td><a href="{{ $motor->dokumen_kepemilikan }}" target="_blank">Lihat Dokumen</a></td></tr>
        <tr><th>Deskripsi</th><td>{{ $motor->deskripsi }}</td></tr>
    </table>
    <a href="{{ route('crud-motors.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection