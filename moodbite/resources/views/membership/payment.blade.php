@extends('layouts.app')

@section('title', 'Pembayaran - MoodBite')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <!-- Order Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #FF6B8B;">Ringkasan Pesanan</h5>
                        <div class="p-3 rounded" style="background-color: #FFF5F5;">
                            <p class="mb-2"><strong>Paket:</strong> {{ ucfirst($membership->type) }}</p>
                            <p class="mb-2"><strong>Harga:</strong> Rp {{ number_format($membership->price, 0, ',', '.') }}</p>
                            <p class="mb-0"><strong>Order ID:</strong> {{ $membership->order_id }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 style="color: #FF6B8B;">Metode Pembayaran</h5>
                        <div class="p-3 rounded" style="background-color: #FFF5F5;">
                            <p class="mb-2">Pilih metode pembayaran di halaman berikutnya</p>
                            <p class="small text-muted mb-0">Kami mendukung berbagai metode pembayaran: Kartu Kredit/Debit, E-Wallet, Virtual Account, dll.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Instructions -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Instruksi Pembayaran</h6>
                    <ol class="mb-0">
                        <li>Klik tombol <strong>"Bayar Sekarang"</strong> di bawah</li>
                        <li>Pilih metode pembayaran yang diinginkan</li>
                        <li>Ikuti instruksi pembayaran hingga selesai</li>
                        <li>Status membership akan otomatis aktif setelah pembayaran berhasil</li>
                    </ol>
                </div>
                
                <!-- Snap Payment Button -->
                <div class="text-center mt-4">
                    <button id="pay-button" class="btn btn-primary btn-lg">
                        <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                    </button>
                    
                    <a href="{{ route('membership.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Snap JavaScript -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function(){
        // Snap Token from controller
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                // Redirect to success page
                window.location.href = '{{ route("membership.callback") }}';
            },
            onPending: function(result){
                // Redirect to callback page
                window.location.href = '{{ route("membership.callback") }}';
            },
            onError: function(result){
                alert("Pembayaran gagal!");
                window.location.href = '{{ route("membership.index") }}';
            },
            onClose: function(){
                alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
            }
        });
    };
</script>
@endsection