@extends('layouts.app')

@section('title', 'Premium Aktif - MoodBite')

@section('content')
@php
    $membership = $currentMembership ?? null;
@endphp

<div class="container py-5">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success rounded-4 shadow-sm border-0 d-flex justify-content-between align-items-center px-4 py-3 mb-4">
            <div>
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- SUCCESS CARD --}}
    <div class="success-wrap mx-auto">
        <div class="success-card shadow-sm rounded-4 overflow-hidden">

            {{-- TOP HERO --}}
            <div class="success-hero text-center p-5">
                <div class="check-icon mx-auto mb-4">
                    <i class="fas fa-check"></i>
                </div>

                <h2 class="fw-bold text-white mb-2">Pembayaran Berhasil! 🎉</h2>
                <p class="text-white-50 mb-0">
                    Selamat {{ Auth::user()->name }}, kamu sekarang adalah <b>Premium Member MoodBite</b>.
                </p>

                <div class="premium-pill mt-4">
                    <i class="fas fa-crown me-2"></i> PREMIUM MEMBER
                </div>
            </div>


            {{-- DETAIL SECTION --}}
            <div class="p-4 p-md-5">

                <div class="detail-box p-4 rounded-4 mb-4">
                    <h5 class="fw-bold text-pink mb-3">
                        <i class="fas fa-receipt me-2"></i>Detail Membership
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="detail-item">
                                <span class="detail-label">Paket</span>
                                <span class="detail-value">{{ ucfirst($membership->type ?? '-') }}</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <span class="detail-label">Berlaku Sampai</span>
                                <span class="detail-value">
                                    {{ $membership && $membership->end_date ? \Carbon\Carbon::parse($membership->end_date)->format('d F Y') : '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <span class="detail-label">Order ID</span>
                                <span class="detail-value">{{ $membership->order_id ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- BENEFITS --}}
                <div class="benefit-box p-4 rounded-4 mb-4">
                    <h5 class="fw-bold text-pink mb-3">
                        <i class="fas fa-gift me-2"></i>Benefit Premium Kamu
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="benefit-item">
                                <i class="fas fa-book-open"></i>
                                <div>
                                    <div class="fw-bold">Resep Eksklusif</div>
                                    <div class="small text-muted">Akses resep premium dari chef ternama</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="benefit-item">
                                <i class="fas fa-bolt"></i>
                                <div>
                                    <div class="fw-bold">Unlimited Recommendation</div>
                                    <div class="small text-muted">Cari rekomendasi makanan tanpa batas</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="benefit-item">
                                <i class="fas fa-star"></i>
                                <div>
                                    <div class="fw-bold">Detail Rekomendasi</div>
                                    <div class="small text-muted">Lebih lengkap: deskripsi & opsi lebih banyak</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="benefit-item">
                                <i class="fas fa-heart"></i>
                                <div>
                                    <div class="fw-bold">History & Favorit</div>
                                    <div class="small text-muted">Simpan favorit & riwayat tanpa batas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- CTA BUTTONS --}}
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <a href="{{ route('premium.recipes.index') }}" class="btn btn-premium px-4 py-3 rounded-4 fw-bold">
                        <i class="fas fa-crown me-2"></i>Mulai Lihat Resep Premium
                    </a>

                    <a href="{{ route('dashboard') }}" class="btn btn-outline-premium px-4 py-3 rounded-4 fw-bold">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>


<style>
:root{
    --pink:#FF6B8B;
    --pink2:#C8A2C8;
    --pink-soft:#FFF0F5;
}

.text-pink{ color: var(--pink) !important; }

/* WRAPPER */
.success-wrap{
    max-width: 900px;
}

/* CARD */
.success-card{
    background: #fff;
    border: 1px solid rgba(255,107,139,.15);
}

/* HERO */
.success-hero{
    background: linear-gradient(135deg, rgba(255,107,139,.96), rgba(200,162,200,.96));
    position: relative;
}
.success-hero:after{
    content:"";
    position:absolute;
    left:0; right:0; bottom:-1px;
    height:40px;
    background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,1));
}

/* CHECK ICON */
.check-icon{
    width: 90px;
    height: 90px;
    border-radius: 999px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.25);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size: 40px;
    color:#fff;
    animation: pop .6s ease;
}
@keyframes pop{
    0%{ transform: scale(.7); opacity:.2; }
    100%{ transform: scale(1); opacity:1; }
}

/* PREMIUM PILL */
.premium-pill{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding: 10px 16px;
    border-radius: 999px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.28);
    font-weight: 900;
    color:#fff;
}

/* DETAIL BOX */
.detail-box{
    background: rgba(255,107,139,.06);
    border: 1px solid rgba(255,107,139,.15);
}
.detail-item{
    display:flex;
    flex-direction:column;
    gap:6px;
    padding: 12px;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(0,0,0,.06);
}
.detail-label{
    font-size: 12px;
    font-weight: 800;
    color: rgba(0,0,0,.45);
}
.detail-value{
    font-size: 14px;
    font-weight: 900;
}

/* BENEFITS */
.benefit-box{
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(0,0,0,.08);
}
.benefit-item{
    display:flex;
    gap: 14px;
    align-items:flex-start;
    padding: 14px;
    border-radius: 18px;
    background: rgba(255,107,139,.05);
    border: 1px solid rgba(255,107,139,.12);
}
.benefit-item i{
    font-size: 22px;
    color: var(--pink);
    margin-top: 3px;
}

/* CTA BUTTONS */
.btn-premium{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    border: none;
    color: #fff;
    transition: .25s ease;
}
.btn-premium:hover{
    filter: brightness(.95);
    transform: translateY(-2px);
    box-shadow: 0 16px 35px rgba(255,107,139,.22);
    color:#fff;
}

.btn-outline-premium{
    border: 2px solid rgba(255,107,139,.35);
    background: transparent;
    color: var(--pink);
    transition: .25s ease;
}
.btn-outline-premium:hover{
    background: rgba(255,107,139,.08);
    transform: translateY(-2px);
}
</style>
@endsection
