@extends('layouts.app')

@section('title', 'Membership Premium - MoodBite')

@section('content')
<div class="container py-5">

    {{-- HERO SECTION --}}
    <div class="premium-hero mb-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4">

            <div>
                <span class="hero-pill">
                    <i class="fas fa-crown me-2"></i> MoodBite Premium
                </span>

                <h1 class="hero-title mt-3 mb-2">
                    Upgrade ke Premium ✨
                </h1>

                <p class="hero-sub">
                    Unlock akses resep eksklusif chef ternama, rekomendasi tanpa batas, dan pengalaman MoodBite paling maksimal.
                </p>

                @if(!$user->isPremium())
                    <div class="hero-cta mt-4">
                        <a href="#plans" class="btn btn-premium-hero">
                            <i class="fas fa-bolt me-2"></i> Lihat Paket Premium
                        </a>
                        <span class="hero-note">
                            ✅ Bisa batal kapan saja • ✅ upgrade langsung aktif
                        </span>
                    </div>
                @endif
            </div>

            <div class="hero-status">
                <div class="hero-status-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small">Status Membership</div>

                            @if($user->isPremium())
                                <div class="fw-bold text-white">Premium Aktif</div>
                                <div class="text-white-50 small">
                                    Sampai {{ $currentMembership->end_date->format('d F Y') }}
                                    • {{ $currentMembership->type_label }}
                                </div>
                            @else
                                <div class="fw-bold text-white">Free Member</div>
                                <div class="text-white-50 small">
                                    Upgrade untuk membuka semua fitur
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('membership.history') }}" class="btn btn-ghost w-100 mt-3">
                    <i class="fas fa-history me-2"></i> Riwayat Membership
                </a>
            </div>

        </div>
    </div>

    {{-- ALERT PREMIUM ACTIVE --}}
    @if($user->isPremium())
        <div class="premium-alert mb-5">
            <div class="d-flex align-items-center gap-3">
                <div class="alert-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Anda sudah Premium 🎉</div>
                    <div class="text-muted">
                        Aktif sampai <strong>{{ $currentMembership->end_date->format('d F Y') }}</strong>
                        ({{ $currentMembership->type_label }})
                    </div>
                </div>
                <span class="badge badge-premium">
                    <i class="fas fa-sparkles me-1"></i> VIP
                </span>
            </div>
        </div>
    @endif

    {{-- PLANS --}}
    <div id="plans" class="row g-4 mb-5 align-items-stretch">
        @foreach($plans as $type => $plan)
            <div class="col-md-4">
                <div class="plan-card {{ $plan['best_value'] ? 'is-popular' : '' }} h-100">

                    @if($plan['badge'])
                        <div class="plan-badge">
                            {{ $plan['badge'] }}
                        </div>
                    @endif

                    <div class="plan-header">
                        <h5 class="fw-bold mb-1">{{ $plan['name'] }}</h5>
                        <p class="text-muted small mb-0">Paket {{ $plan['period'] }}</p>

                        <div class="price mt-3">
                            <span class="price-number">
                                Rp {{ number_format($plan['price'], 0, ',', '.') }}
                            </span>
                            <span class="price-period">/{{ $plan['period'] }}</span>
                        </div>
                    </div>

                    <div class="plan-body">
                        <ul class="feature-list">
                            @foreach($plan['features'] as $feature)
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="plan-footer mt-auto">
                        @if($user->isPremium() && $user->premium_type == $type)
                            <button class="btn btn-active w-100" disabled>
                                <i class="fas fa-check me-2"></i> Paket Aktif
                            </button>
                        @else
                            <form action="{{ route('membership.purchase') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type }}">
                                <button type="submit"
                                    class="btn w-100 {{ $plan['best_value'] ? 'btn-premium' : 'btn-outline-premium' }}">
                                    <i class="fas fa-crown me-2"></i> Pilih Paket
                                </button>
                            </form>
                        @endif

                        <div class="secure-note">
                            <i class="fas fa-lock me-1"></i> Pembayaran aman • akses langsung aktif
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    {{-- BENEFITS SECTION --}}
    <div class="benefit-card">
        <div class="text-center mb-5">
            <h3 class="fw-bold mb-2">Kenapa Harus Premium?</h3>
            <p class="text-muted mb-0">Karena MoodBite Premium bikin hidupmu lebih mudah, enak, & seru.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="benefit-item">
                    <div class="benefit-icon bg-gold"><i class="fas fa-star"></i></div>
                    <div>
                        <div class="fw-bold">Unlimited Rekomendasi</div>
                        <div class="text-muted small">Tidak dibatasi rekomendasi harian lagi — bebas cari kapan saja.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="benefit-item">
                    <div class="benefit-icon bg-pink"><i class="fas fa-utensils"></i></div>
                    <div>
                        <div class="fw-bold">Resep Eksklusif Chef</div>
                        <div class="text-muted small">Resep premium asli dari chef ternama + langkah lengkap.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="benefit-item">
                    <div class="benefit-icon bg-mint"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="fw-bold">History Tanpa Batas</div>
                        <div class="text-muted small">Tracking semua rekomendasi kamu kapan pun kamu butuh.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="benefit-item">
                    <div class="benefit-icon bg-purple"><i class="fas fa-ban"></i></div>
                    <div>
                        <div class="fw-bold">Tanpa Iklan</div>
                        <div class="text-muted small">Focus ke makanan tanpa gangguan dan lebih nyaman dipakai.</div>
                    </div>
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
    --shadow: 0 18px 45px rgba(255,107,139,.18);
    --shadow2: 0 10px 28px rgba(0,0,0,.06);
}

/* HERO */
.premium-hero{
    border-radius: 28px;
    padding: 32px;
    background: radial-gradient(1200px 400px at 15% 0%, rgba(255,255,255,.55), transparent 60%),
                linear-gradient(135deg, rgba(255,107,139,.30), rgba(200,162,200,.22)),
                #fff;
    box-shadow: var(--shadow2);
    border: 1px solid rgba(255,107,139,.18);
    position: relative;
    overflow: hidden;
}
.hero-pill{
    display:inline-flex;
    gap:8px;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 800;
    color: var(--pink);
    background: rgba(255,107,139,.12);
    border: 1px solid rgba(255,107,139,.18);
}
.hero-title{
    font-size: 2.3rem;
    letter-spacing: -1px;
}
.hero-sub{
    max-width: 520px;
    color: rgba(0,0,0,.55);
}
.hero-cta{
    display:flex;
    gap: 14px;
    flex-wrap: wrap;
    align-items:center;
}
.btn-premium-hero{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    color: #fff;
    border: none;
    padding: 12px 18px;
    border-radius: 16px;
    font-weight: 900;
}
.btn-premium-hero:hover{ filter: brightness(.95); color:#fff; }
.hero-note{
    font-size: 13px;
    font-weight: 600;
    color: rgba(0,0,0,.55);
}

/* STATUS CARD */
.hero-status{ max-width: 340px; width: 100%; }
.hero-status-card{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    border-radius: 18px;
    padding: 18px;
    box-shadow: var(--shadow);
}
.hero-icon{
    width: 46px; height: 46px;
    display:flex;
    justify-content:center;
    align-items:center;
    border-radius: 16px;
    background: rgba(255,255,255,.18);
    color: #fff;
}
.btn-ghost{
    border: 2px solid rgba(255,107,139,.35);
    background: rgba(255,255,255,.85);
    color: var(--pink);
    font-weight: 700;
    border-radius: 14px;
    padding: 10px 14px;
}
.btn-ghost:hover{
    background: rgba(255,107,139,.10);
    color: var(--pink);
}

/* ALERT */
.premium-alert{
    border-radius: 20px;
    padding: 18px;
    background: linear-gradient(135deg, #fff, rgba(255,107,139,.08));
    border: 1px solid rgba(255,107,139,.18);
    box-shadow: var(--shadow2);
}
.alert-icon{
    width: 46px; height: 46px;
    border-radius: 16px;
    background: rgba(255,107,139,.12);
    color: var(--pink);
    display:flex;
    align-items:center;
    justify-content:center;
}
.badge-premium{
    padding: 8px 14px;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--pink), #FFD166);
    color:#fff;
    font-weight: 900;
}

/* PLANS */
.plan-card{
    border-radius: 24px;
    background: #fff;
    box-shadow: var(--shadow2);
    border: 1px solid rgba(255,107,139,.15);
    overflow: hidden;
    display:flex;
    flex-direction: column;
    height: 100%;
    transition: .25s ease;
    position: relative;
}
.plan-card:hover{
    transform: translateY(-6px);
    box-shadow: var(--shadow);
}
.plan-card.is-popular{
    border: 2px solid rgba(255,107,139,.55);
    box-shadow: var(--shadow);
}
.plan-badge{
    position:absolute;
    top:16px;
    right:16px;
    background: linear-gradient(135deg, var(--pink), #FFD166);
    color:#fff;
    font-weight: 900;
    font-size: 12px;
    padding: 7px 12px;
    border-radius: 999px;
}
.plan-header{
    padding: 20px;
    background: radial-gradient(700px 200px at 20% 0%, rgba(255,107,139,.12), transparent 60%),
                linear-gradient(135deg, #fff, rgba(255,240,245,.50));
}
.price-number{
    font-size: 1.7rem;
    font-weight: 900;
    color: var(--pink);
}
.price-period{
    font-size: 14px;
    color: rgba(0,0,0,.55);
    margin-left: 6px;
}
.plan-body{
    padding: 18px 20px 0;
    flex-grow:1;
}
.feature-list{
    list-style:none;
    padding:0;
    margin:0;
    display:flex;
    flex-direction:column;
    gap: 10px;
}
.feature-list li{
    display:flex;
    gap: 10px;
    align-items:flex-start;
    font-weight: 600;
    color: rgba(0,0,0,.72);
}
.feature-list i{
    color: #2ecc71;
    margin-top: 2px;
}
.plan-footer{
    padding: 18px 20px 22px;
}
.secure-note{
    margin-top: 12px;
    font-size: 12px;
    text-align:center;
    color: rgba(0,0,0,.55);
}

/* BUTTONS */
.btn-premium{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    border: none;
    border-radius: 14px;
    padding: 12px;
    color: #fff;
    font-weight: 900;
}
.btn-premium:hover{ filter: brightness(.96); color:#fff; }

.btn-outline-premium{
    border: 2px solid rgba(255,107,139,.45);
    background: transparent;
    border-radius: 14px;
    padding: 12px;
    color: var(--pink);
    font-weight: 900;
}
.btn-outline-premium:hover{
    background: rgba(255,107,139,.10);
    color: var(--pink);
}
.btn-active{
    background: rgba(25,135,84,.12);
    border: 2px solid rgba(25,135,84,.30);
    color: #198754;
    border-radius: 14px;
    padding: 12px;
    font-weight: 900;
}

/* BENEFITS */
.benefit-card{
    border-radius: 26px;
    padding: 32px;
    background: radial-gradient(900px 300px at 20% 0%, rgba(255,107,139,.10), transparent 60%),
                linear-gradient(135deg, #fff, rgba(240,248,255,.60));
    border: 1px solid rgba(255,107,139,.14);
    box-shadow: var(--shadow2);
}
.benefit-item{
    display:flex;
    gap: 14px;
    align-items:flex-start;
    border-radius: 18px;
    padding: 16px;
    background: rgba(255,255,255,.75);
    border: 1px solid rgba(0,0,0,.06);
}
.benefit-icon{
    width: 48px;
    height: 48px;
    border-radius: 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    flex-shrink:0;
    box-shadow: 0 12px 24px rgba(0,0,0,.10);
}
.bg-gold{ background: linear-gradient(135deg, #FFD166, #FF6B8B); }
.bg-pink{ background: linear-gradient(135deg, #FF6B8B, #C8A2C8); }
.bg-mint{ background: linear-gradient(135deg, #98FF98, #2ECC71); }
.bg-purple{ background: linear-gradient(135deg, #6C63FF, #FF6B8B); }

/* RESPONSIVE */
@media (max-width: 991.98px){
    .hero-title{ font-size: 1.7rem; }
}
</style>
@endsection
