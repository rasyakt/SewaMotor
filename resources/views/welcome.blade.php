<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sewa Motor - Solusi Terbaik untuk Kebutuhan Transportasi Anda</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="fas fa-motorcycle"></i> SewaMotor
            </a>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Sewa Motor Mudah & Terpercaya</h1>
            <p class="lead mb-5">Solusi terbaik untuk kebutuhan transportasi harian, perjalanan bisnis, atau liburan Anda</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('register') }}?role=penyewa" class="btn btn-light btn-lg w-100">
                                <i class="fas fa-user me-2"></i>Daftar sebagai Penyewa
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('register') }}?role=pemilik" class="btn btn-outline-light btn-lg w-100">
                                <i class="fas fa-user-tie me-2"></i>Daftar sebagai Pemilik
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('login') }}" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Mengapa Memilih SewaMotor?</h2>
                    <p class="lead text-muted">Kami menyediakan solusi lengkap untuk semua kebutuhan sewa motor Anda</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Aman & Terpercaya</h4>
                    <p class="text-muted">Semua motor telah diverifikasi dan memiliki dokumen lengkap</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Proses Cepat</h4>
                    <p class="text-muted">Pemesanan dan pembayaran dapat dilakukan dalam hitungan menit</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>Support 24/7</h4>
                    <p class="text-muted">Tim customer service siap membantu kapan saja</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-number">500+</div>
                    <p class="text-muted">Motor Tersedia</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">1,000+</div>
                    <p class="text-muted">Penyewa Aktif</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">200+</div>
                    <p class="text-muted">Pemilik Terdaftar</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">99%</div>
                    <p class="text-muted">Kepuasan Pelanggan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Siap Memulai?</h2>
            <p class="lead text-muted mb-4">Bergabunglah dengan ribuan pengguna yang telah mempercayakan kebutuhan transportasi mereka kepada kami</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('register') }}?role=penyewa" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-user me-2"></i>Mulai Menyewa
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('register') }}?role=pemilik" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-user-tie me-2"></i>Daftarkan Motor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 SewaMotor. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>