@extends('layouts.guest')

@section('title', 'Daftar - Sewa Motor')
@section('subtitle', 'Buat Akun Baru Anda')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <label for="nama" class="form-label">
            <i class="fas fa-user"></i> Nama Lengkap
        </label>
        <input 
            id="nama" 
            type="text" 
            class="form-control @error('nama') is-invalid @enderror" 
            name="nama" 
            value="{{ old('nama') }}" 
            required 
            autocomplete="name" 
            autofocus
            placeholder="Masukkan nama lengkap"
        >
        @error('nama')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email" class="form-label">
            <i class="fas fa-envelope"></i> Email Address
        </label>
        <input 
            id="email" 
            type="email" 
            class="form-control @error('email') is-invalid @enderror" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autocomplete="email"
            placeholder="Masukkan email Anda"
        >
        @error('email')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="no_tlpn" class="form-label">
            <i class="fas fa-phone"></i> No Telepon
        </label>
        <input 
            id="no_tlpn" 
            type="text" 
            class="form-control @error('no_tlpn') is-invalid @enderror" 
            name="no_tlpn" 
            value="{{ old('no_tlpn') }}" 
            required
            placeholder="Contoh: 081234567890"
        >
        @error('no_tlpn')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="role" class="form-label">
            <i class="fas fa-user-tag"></i> Daftar Sebagai
        </label>
        <select 
            id="role" 
            class="form-select @error('role') is-invalid @enderror" 
            name="role" 
            required
        >
            <option value="">-- Pilih Peran --</option>
            <option value="penyewa" {{ old('role') == 'penyewa' ? 'selected' : '' }}>
                <i class="fas fa-user-check"></i> Penyewa (Pencari Motor)
            </option>
            <option value="pemilik" {{ old('role') == 'pemilik' ? 'selected' : '' }}>
                <i class="fas fa-motorcycle"></i> Pemilik Motor
            </option>
        </select>
        @error('role')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password" class="form-label">
            <i class="fas fa-lock"></i> Password
        </label>
        <div class="input-group">
            <input 
                id="password" 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
            >
            <button type="button" class="btn-toggle-password" id="togglePassword">
                <i class="fa fa-eye" id="eyeIcon"></i>
            </button>
        </div>
        @error('password')
            <span class="invalid-feedback" style="display: block;">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password-confirm" class="form-label">
            <i class="fas fa-lock-open"></i> Konfirmasi Password
        </label>
        <div class="input-group">
            <input 
                id="password-confirm" 
                type="password" 
                class="form-control" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="Ulangi password Anda"
            >
            <button type="button" class="btn-toggle-password" id="togglePasswordConfirm">
                <i class="fa fa-eye" id="eyeIconConfirm"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn-submit">
        <i class="fas fa-user-plus"></i> Daftar Sekarang
    </button>
</form>
@endsection

@section('footer-link')
    Sudah punya akun? 
    <a href="{{ route('login') }}">
        Masuk di sini
    </a>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function(e) {
                e.preventDefault();
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        }

        // Password confirm toggle
        const passwordConfirmInput = document.getElementById('password-confirm');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        if (togglePasswordConfirm && passwordConfirmInput) {
            togglePasswordConfirm.addEventListener('click', function(e) {
                e.preventDefault();
                const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmInput.setAttribute('type', type);
                eyeIconConfirm.classList.toggle('fa-eye');
                eyeIconConfirm.classList.toggle('fa-eye-slash');
            });
        }
    });
</script>
@endpush