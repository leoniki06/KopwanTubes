@extends('layouts.app')

@section('title', 'Pembayaran - MoodBite')

@section('content')
@php
    $isMock = env('PAYMENT_MODE') === 'mock';
@endphp

<div class="container py-5">

    {{-- HERO HEADER --}}
    <div class="pay-hero mb-4 p-4 rounded-4 shadow-sm">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="hero-pill">
                    <i class="fas fa-shield-heart me-2"></i>Secure Payment
                </span>
                <h3 class="fw-bold text-white mt-3 mb-1">Selesaikan Pembayaran ✨</h3>
                <p class="text-white-50 mb-0">
                    Setelah pembayaran berhasil, membership premium langsung aktif.
                </p>
            </div>

            <div class="text-end">
                <div class="price-tag">
                    Rp {{ number_format($membership->price,0,',','.') }}
                    <small>/{{ $membership->type }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-4">

        {{-- LEFT: SUMMARY --}}
        <div class="col-lg-6">
            <div class="card pay-card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold text-pink mb-3">
                        <i class="fas fa-receipt me-2"></i>Ringkasan Pesanan
                    </h5>

                    <div class="summary-box rounded-4 p-4 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Paket</small>
                                <div class="fw-bold fs-5">{{ ucfirst($membership->type) }}</div>
                            </div>

                            <div class="text-end">
                                <small class="text-muted">Total</small>
                                <div class="fw-bold fs-4 text-pink">
                                    Rp {{ number_format($membership->price,0,',','.') }}
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Order ID</span>
                            <span class="fw-semibold">{{ $membership->order_id }}</span>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                {{ strtoupper($membership->payment_status ?? 'pending') }}
                            </span>
                        </div>
                    </div>

                    <div class="info-box rounded-4 p-4">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-info-circle me-2"></i>Catatan
                        </h6>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>Upgrade langsung aktif setelah pembayaran berhasil</li>
                            <li>Kamu bisa cek status di riwayat membership</li>
                            <li>Kalau gagal, kamu bisa ulang dari halaman membership</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT: PAYMENT --}}
        <div class="col-lg-6">
            <div class="card pay-card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold text-pink mb-3">
                        <i class="fas fa-credit-card me-2"></i>Metode Pembayaran
                    </h5>

                    @if($isMock)
                        {{-- MOCK MODE --}}
                        <form method="POST" action="{{ route('membership.payMock', $membership->order_id) }}">
                            @csrf

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="qris" required>
                                        <span><i class="fas fa-qrcode me-2"></i>QRIS</span>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="gopay">
                                        <span><i class="fas fa-wallet me-2"></i>GoPay</span>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="bca">
                                        <span><i class="fas fa-university me-2"></i>VA BCA</span>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="mandiri">
                                        <span><i class="fas fa-university me-2"></i>VA Mandiri</span>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-pay w-100 py-3 fw-bold rounded-4">
                                <i class="fas fa-check me-2"></i>Bayar Sekarang (Simulasi)
                            </button>

                            <p class="text-center text-muted small mt-3 mb-0">
                                * Ini simulasi pembayaran (tanpa uang asli)
                            </p>
                        </form>

                    @else
                        {{-- MIDTRANS SNAP MODE --}}
                        <div class="pay-note rounded-4 p-4 mb-4">
                            <p class="mb-2 fw-semibold">
                                <i class="fas fa-lock me-2"></i>Midtrans Snap Payment
                            </p>
                            <p class="small text-muted mb-0">
                                Klik <strong>Bayar Sekarang</strong>, lalu pilih metode pembayaran seperti QRIS, VA, E-Wallet, dan lainnya.
                            </p>
                        </div>

                        <div class="d-flex gap-2 flex-wrap mb-4">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-qrcode me-1"></i> QRIS
                            </span>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-wallet me-1"></i> E-Wallet
                            </span>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-university me-1"></i> Virtual Account
                            </span>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-credit-card me-1"></i> Kartu
                            </span>
                        </div>

                        <button id="pay-button" class="btn btn-pay w-100 py-3 fw-bold rounded-4">
                            <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                        </button>

                        <a href="{{ route('membership.index') }}" class="btn btn-back w-100 mt-3 py-3 fw-bold rounded-4">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>

                        <div class="text-center small text-muted mt-3">
                            Dengan melanjutkan, kamu menyetujui <b>Terms</b> & <b>Privacy</b>.
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>

{{-- MIDTRANS SNAP JS (ONLY IF NOT MOCK) --}}
@if(!$isMock)
<script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button').onclick = function(){

    const payBtn = document.getElementById('pay-button');
    payBtn.disabled = true;
    payBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>Memproses...`;

    window.snap.pay('{{ $snapToken }}', {

        onSuccess: function(result){
            // ✅ KIRIM KE BACKEND → UPGRADE USER PREMIUM
            fetch("{{ route('membership.finish') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(result)
            }).then(() => {
                window.location.href = "{{ route('membership.success') }}";
            });
        },

        onPending: function(result){
            alert("Pembayaran masih pending. Silakan selesaikan pembayaran dulu.");
            window.location.href = "{{ route('membership.history') }}";
        },

        onError: function(result){
            alert("Pembayaran gagal! Silakan coba lagi.");
            window.location.href = "{{ route('membership.index') }}";
        },

        onClose: function(){
            alert("Kamu menutup pembayaran sebelum selesai.");
            payBtn.disabled = false;
            payBtn.innerHTML = `<i class="fas fa-credit-card me-2"></i>Bayar Sekarang`;
        }
    });
};
</script>
@endif

<style>
:root{
    --pink:#FF6B8B;
    --pink2:#C8A2C8;
    --pink-soft:#FFF0F5;
}

.text-pink{ color: var(--pink) !important; }

/* HERO */
.pay-hero{
    background: linear-gradient(135deg, rgba(255,107,139,.95), rgba(200,162,200,.95));
    border: 1px solid rgba(255,255,255,.25);
}
.hero-pill{
    display:inline-flex;
    align-items:center;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 13px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
}
.price-tag{
    font-size: 32px;
    font-weight: 900;
    color: #fff;
    text-shadow: 0 10px 30px rgba(0,0,0,.12);
}
.price-tag small{
    font-size: 14px;
    font-weight: 700;
    color: rgba(255,255,255,.85);
}

/* CARDS */
.pay-card{
    border: 1px solid rgba(255,107,139,.14);
}
.summary-box{
    background: linear-gradient(135deg, rgba(255,107,139,.07), rgba(200,162,200,.07));
    border: 1px solid rgba(255,107,139,.12);
}
.info-box{
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(0,0,0,.08);
}

/* PAYMENT NOTE */
.pay-note{
    background: rgba(255,107,139,.07);
    border: 1px solid rgba(255,107,139,.18);
}

/* PAYMENT OPTION */
.payment-option{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    padding:16px;
    border-radius:16px;
    border:2px solid rgba(255,107,139,.15);
    cursor:pointer;
    font-weight:800;
    background:#fff;
    transition:.2s;
    height: 70px;
}
.payment-option:hover{
    background:rgba(255,107,139,.08);
    border-color:rgba(255,107,139,.35);
}
.payment-option input{
    display:none;
}
.payment-option input:checked + span{
    color:var(--pink);
}

/* BUTTONS */
.btn-pay{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    border: none;
    color: #fff;
    transition: .25s ease;
}
.btn-pay:hover{
    filter: brightness(.95);
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(255,107,139,.22);
    color:#fff;
}

.btn-back{
    border: 2px solid rgba(255,107,139,.28);
    background: transparent;
    color: var(--pink);
    transition: .25s ease;
}
.btn-back:hover{
    background: rgba(255,107,139,.08);
    transform: translateY(-2px);
}
</style>
@endsection
