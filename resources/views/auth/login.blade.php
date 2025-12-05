@extends('layouts.guest')

@section('title', 'Login - Sewa Motor')
@section('subtitle', 'Masuk ke Akun Anda')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

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
            autofocus
            placeholder="Masukkan email Anda"
        >
        @error('email')
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
                autocomplete="current-password"
                placeholder="Masukkan password Anda"
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
        <div class="form-check">
            <input 
                class="form-check-input" 
                type="checkbox" 
                name="remember" 
                id="remember" 
                {{ old('remember') ? 'checked' : '' }}
            >
            <label class="form-check-label" for="remember">
                Ingat saya
            </label>
        </div>
    </div>

    <button type="submit" class="btn-submit">
        <i class="fas fa-sign-in-alt"></i> Masuk
    </button>

    @if (Route::has('password.request'))
        <div style="text-align: center; margin-top: 15px;">
            <a href="{{ route('password.request') }}" class="forgot-password-link">
                <i class="fas fa-question-circle"></i> Lupa Password?
            </a>
        </div>
    @endif
</form>
@endsection

@section('footer-link')
    Belum punya akun? 
    <a href="{{ route('register') }}">
        Daftar sekarang
    </a>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endpush
