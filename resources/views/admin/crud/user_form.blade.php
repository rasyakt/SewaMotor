@extends('layouts.app')
@section('content')
<div class="container">
    <h2>{{ isset($user) ? 'Edit User' : 'Tambah User' }}</h2>
    <form method="POST" action="{{ isset($user) ? route('crud-users.update', $user->id) : route('crud-users.store') }}">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_tlpn" class="form-control" value="{{ old('no_tlpn', $user->no_tlpn ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pemilik" {{ old('role', $user->role ?? '') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                <option value="penyewa" {{ old('role', $user->role ?? '') == 'penyewa' ? 'selected' : '' }}>Penyewa</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
            @if(isset($user))
                <small>Kosongkan jika tidak ingin mengubah password</small>
            @endif
        </div>
        <button type="submit" class="btn btn-success">{{ isset($user) ? 'Update' : 'Simpan' }}</button>
        <a href="{{ route('crud-users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection