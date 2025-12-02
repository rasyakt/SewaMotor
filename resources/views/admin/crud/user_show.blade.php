@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Detail User</h2>
    <table class="table">
        <tr><th>ID</th><td>{{ $user->id }}</td></tr>
        <tr><th>Nama</th><td>{{ $user->nama }}</td></tr>
        <tr><th>Email</th><td>{{ $user->email }}</td></tr>
        <tr><th>No Telepon</th><td>{{ $user->no_tlpn }}</td></tr>
        <tr><th>Role</th><td>{{ $user->role }}</td></tr>
    </table>
    <a href="{{ route('crud-users.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection