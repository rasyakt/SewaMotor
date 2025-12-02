@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Detail Tarif Rental</h2>
    <table class="table">
        <tr><th>ID</th><td>{{ $tarif->id }}</td></tr>
        <tr><th>Motor ID</th><td>{{ $tarif->motor_id }}</td></tr>
        <tr><th>Tarif Harian</th><td>{{ $tarif->tarif_harian }}</td></tr>
        <tr><th>Tarif Mingguan</th><td>{{ $tarif->tarif_mingguan }}</td></tr>
        <tr><th>Tarif Bulanan</th><td>{{ $tarif->tarif_bulanan }}</td></tr>
    </table>
    <a href="{{ route('crud-tarif-rentals.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection