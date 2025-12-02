@extends('layouts.app')

@section('title', 'Pembayaran - ' . $penyewaan->motor->merk)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-credit-card"></i> Form Pembayaran</h4>
            </div>
            <div class="card-body">
                <!-- Informasi Penyewaan -->
                <div class="alert alert-info">
                    <h5>Detail Penyewaan:</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Motor:</strong> {{ $penyewaan->motor->merk }}<br>
                            <strong>Plat:</strong> {{ $penyewaan->motor->no_plat }}<br>
                            <strong>CC:</strong> {{ $penyewaan->motor->tipe_cc }} CC
                        </div>
                        <div class="col-md-6">
                            <strong>Periode:</strong> 
                            {{ \Carbon\Carbon::parse($penyewaan->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($penyewaan->tanggal_selesai)->format('d M Y') }}<br>
                            <strong>Durasi:</strong> {{ $penyewaan->hitungDurasi() }} hari<br>
                            <strong>Tipe:</strong> {{ ucfirst($penyewaan->tipe_durasi) }}
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-success mb-0">Total Pembayaran: Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</h5>
                </div>

                <form action="{{ route('customer.payments.process', $penyewaan->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fs-5">Pilih Metode Pembayaran</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card method-card" data-method="transfer">
                                    <div class="card-body text-center">
                                        <i class="fas fa-university fa-2x text-primary mb-2"></i>
                                        <h6>Transfer Bank</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" 
                                                   id="transfer" value="transfer" checked>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card method-card" data-method="cash">
                                    <div class="card-body text-center">
                                        <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                        <h6>Cash</h6>
                                        <small class="text-muted">Bayar di Tempat</small>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" 
                                                   id="cash" value="cash">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card method-card" data-method="qris">
                                    <div class="card-body text-center">
                                        <i class="fas fa-qrcode fa-2x text-info mb-2"></i>
                                        <h6>QRIS</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" 
                                                   id="qris" value="qris">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Transfer Section -->
                    <div id="buktiTransferSection" class="mb-4">
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-upload"></i> Upload Bukti Transfer</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label">Pilih File Bukti Transfer</label>
                                    <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" 
                                           id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
                                    @error('bukti_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Format: JPEG, PNG, JPG (Max: 2MB). Pastikan bukti transfer jelas terbaca.
                                    </div>
                                </div>
                                
                                <!-- Preview Image -->
                                <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                                    <img id="preview" class="img-thumbnail" style="max-height: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                        <i class="fas fa-trash"></i> Hapus Gambar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Rekening -->
                    <div id="infoRekening" class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Rekening</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Bank BCA</h6>
                                    <p class="mb-1"><strong>No. Rekening:</strong> 1234567890</p>
                                    <p class="mb-1"><strong>Atas Nama:</strong> Sewa Motor Jaya</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Bank Mandiri</h6>
                                    <p class="mb-1"><strong>No. Rekening:</strong> 0987654321</p>
                                    <p class="mb-1"><strong>Atas Nama:</strong> Sewa Motor Jaya</p>
                                </div>
                            </div>
                            <hr>
                            <div class="alert alert-warning mb-0">
                                <strong>Jumlah Transfer:</strong> Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}<br>
                                <strong>Kode Unik:</strong> {{ substr($penyewaan->id, -3) }} (Tambahkan 3 digit akhir ID pesanan)
                            </div>
                        </div>
                    </div>

                    <!-- Cash Instructions -->
                    <div id="cashInstructions" class="card mb-4" style="display: none;">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-money-bill-wave"></i> Instruksi Pembayaran Cash</h6>
                        </div>
                        <div class="card-body">
                            <p>Silakan datang ke lokasi kami untuk melakukan pembayaran cash:</p>
                            <p class="mb-1"><strong>Alamat:</strong> Jl. Contoh Alamat No. 123, Kota Anda</p>
                            <p class="mb-1"><strong>Jam Operasional:</strong> 08:00 - 17:00 WIB</p>
                            <p class="mb-0"><strong>Telepon:</strong> (021) 1234567</p>
                        </div>
                    </div>

                    <!-- QRIS Instructions -->
                    <div id="qrisInstructions" class="card mb-4" style="display: none;">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-qrcode"></i> Instruksi Pembayaran QRIS</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <!-- Placeholder for QR Code -->
                                <div class="bg-light border rounded p-4 d-inline-block">
                                    <i class="fas fa-qrcode fa-5x text-muted"></i>
                                </div>
                                <p class="mt-2 mb-1">Scan QR code di atas menggunakan aplikasi e-wallet atau mobile banking Anda</p>
                                <p class="text-muted small">Support: GoPay, OVO, DANA, LinkAja, dll.</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-paper-plane"></i> Proses Pembayaran
                        </button>
                        <a href="{{ route('customer.bookings.history') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke History
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="card mt-3">
            <div class="card-body">
                <h6><i class="fas fa-exclamation-triangle text-warning"></i> Penting:</h6>
                <ul class="small">
                    <li>Pastikan jumlah transfer sesuai dengan total pembayaran</li>
                    <li>Simpan bukti transfer sebagai arsip pribadi</li>
                    <li>Pesanan akan diproses setelah pembayaran dikonfirmasi admin</li>
                    <li>Untuk bantuan, hubungi customer service di 081234567890</li>
                    <li>Pembatalan pesanan dapat dilakukan maksimal 2 jam setelah pemesanan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const methodCards = document.querySelectorAll('.method-card');
    const buktiTransferSection = document.getElementById('buktiTransferSection');
    const infoRekening = document.getElementById('infoRekening');
    const cashInstructions = document.getElementById('cashInstructions');
    const qrisInstructions = document.getElementById('qrisInstructions');
    const buktiPembayaran = document.getElementById('bukti_pembayaran');
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('imagePreview');

    // Method selection
    methodCards.forEach(card => {
        card.addEventListener('click', function() {
            const method = this.getAttribute('data-method');
            
            // Update radio button
            document.getElementById(method).checked = true;
            
            // Update card styles
            methodCards.forEach(c => c.classList.remove('border-primary', 'bg-light'));
            this.classList.add('border-primary', 'bg-light');
            
            // Toggle sections
            toggleSections(method);
        });
    });

    // Image preview
    buktiPembayaran.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    function toggleSections(method) {
        // Reset all sections
        buktiTransferSection.style.display = 'none';
        infoRekening.style.display = 'none';
        cashInstructions.style.display = 'none';
        qrisInstructions.style.display = 'none';
        buktiPembayaran.required = false;

        switch(method) {
            case 'transfer':
                buktiTransferSection.style.display = 'block';
                infoRekening.style.display = 'block';
                buktiPembayaran.required = true;
                break;
            case 'cash':
                cashInstructions.style.display = 'block';
                break;
            case 'qris':
                qrisInstructions.style.display = 'block';
                break;
        }
    }

    function removeImage() {
        buktiPembayaran.value = '';
        imagePreview.style.display = 'none';
        preview.src = '';
    }

    // Initialize
    document.querySelector('.method-card[data-method="transfer"]').click();
});

// Form validation
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked').value;
    const buktiFile = document.getElementById('bukti_pembayaran').files[0];
    
    if (selectedMethod === 'transfer' && !buktiFile) {
        e.preventDefault();
        alert('Silakan upload bukti transfer untuk metode pembayaran transfer.');
        return false;
    }
});
</script>

<style>
.method-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}
.method-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.method-card.border-primary {
    border-color: #0d6efd !important;
    background-color: #f8f9fa !important;
}
</style>
@endpush