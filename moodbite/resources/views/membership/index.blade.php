@extends('layouts.app')

@section('title', 'Membership Premium - MoodBite')

@section('content')
<div class="container py-4">

    <!-- HERO -->
    <div class="premium-hero mb-4">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-4">
            <div>
                <span class="hero-pill">
                    <i class="fas fa-crown me-2"></i> MoodBite Premium
                </span>
                <h1 class="hero-title mt-3 mb-2">Upgrade ke Premium ✨</h1>
                <p class="hero-sub mb-0">
                    Dapatkan akses resep eksklusif, rekomendasi premium, dan benefit spesial.
                </p>
            </div>

            <div class="hero-side">
                <div class="hero-side-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small">Status Membership</div>
                            @if($user->isPremium())
                                <div class="text-white fw-semibold">Premium Aktif</div>
                                <div class="text-white-50 small">
                                    Sampai {{ $currentMembership->end_date->format('d F Y') }}
                                    • {{ $currentMembership->type_label }}
                                </div>
                            @else
                                <div class="text-white fw-semibold">Free Member</div>
                                <div class="text-white-50 small">Upgrade untuk buka semua fitur</div>
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

    <!-- CURRENT PREMIUM ALERT -->
    @if($user->isPremium())
        <div class="premium-alert mb-4">
            <div class="d-flex align-items-start gap-3">
                <div class="premium-alert-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Anda sudah Premium 🎉</div>
                    <div class="text-muted">
                        Aktif sampai <strong>{{ $currentMembership->end_date->format('d F Y') }}</strong>
                        ({{ $currentMembership->type_label }})
                    </div>
                </div>
                <div class="d-none d-md-block">
                    <span class="badge badge-premium">
                        <i class="fas fa-sparkles me-1"></i> VIP
                    </span>
                </div>
            </div>
        </div>
    @endif

    <!-- PLANS -->
    <div class="row g-4 mb-5">
        @foreach($plans as $type => $plan)
            <div class="col-md-4">
                <div class="plan-card h-100 {{ $plan['best_value'] ? 'is-popular' : '' }}">
                    @if($plan['best_value'])
                        <div class="plan-badge">
                            <span class="badge">TERPOPULER</span>
                        </div>
                    @endif

                    <div class="plan-head">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <h5 class="mb-1 fw-bold">{{ $plan['name'] }}</h5>
                                <div class="text-muted small">Paket {{ $plan['period'] }}</div>
                            </div>

                            <div class="plan-mark">
                                <i class="fas fa-crown"></i>
                            </div>
                        </div>

                        <div class="plan-price mt-3">
                            <div class="price-number">
                                Rp {{ number_format($plan['price'], 0, ',', '.') }}
                            </div>
                            <div class="text-muted small">per {{ $plan['period'] }}</div>
                        </div>
                    </div>

                    <div class="plan-body">
                        <ul class="feature-list">
                            @foreach($plan['features'] as $feature)
                                <li>
                                    <span class="feature-dot"><i class="fas fa-check"></i></span>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="plan-foot mt-auto">
                        @if($user->isPremium() && $user->premium_type == $type)
                            <button class="btn btn-active w-100" disabled>
                                <i class="fas fa-check me-2"></i> Paket Aktif
                            </button>
                        @else
                            <form action="{{ route('membership.purchase') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type }}">
                                <button type="submit" class="btn w-100 {{ $plan['best_value'] ? 'btn-premium' : 'btn-outline-premium' }}">
                                    <i class="fas fa-crown me-2"></i> Pilih Paket
                                </button>
                            </form>
                        @endif

                        <div class="small text-muted mt-3 text-center">
                            <i class="fas fa-lock me-1"></i> Pembayaran aman • akses langsung aktif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- BENEFITS -->
    <div class="benefit-card">
        <div class="text-center mb-4">
            <h3 class="mb-1 fw-bold" style="color: var(--pink);">Apa yang Anda Dapatkan?</h3>
            <p class="text-muted mb-0">Benefit premium yang bikin pengalaman MoodBite naik level.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="benefit-item">
                    <div class="benefit-icon i-gold"><i class="fas fa-star"></i></div>
                    <div>
                        <div class="fw-semibold">Rekomendasi Premium</div>
                        <div class="text-muted small">Akses restoran eksklusif dan menu premium yang tidak tersedia untuk member biasa.</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="benefit-item">
                    <div class="benefit-icon i-sky"><i class="fas fa-map-marked-alt"></i></div>
                    <div>
                        <div class="fw-semibold">Detail Lengkap</div>
                        <div class="text-muted small">Info lengkap: tips lokasi, jam operasional, dan kontak restoran.</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="benefit-item">
                    <div class="benefit-icon i-mint"><i class="fas fa-utensils"></i></div>
                    <div>
                        <div class="fw-semibold">Resep Eksklusif</div>
                        <div class="text-muted small">Resep khusus dari chef ternama yang bisa kamu masak di rumah.</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="benefit-item">
                    <div class="benefit-icon i-purple"><i class="fas fa-percentage"></i></div>
                    <div>
                        <div class="fw-semibold">Diskon Eksklusif</div>
                        <div class="text-muted small">Diskon khusus di berbagai restoran partner di seluruh Indonesia.</div>
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
    --shadow2: 0 10px 28px rgba(0,0,0,.08);
}

/* HERO */
.premium-hero{
    border-radius: 22px;
    padding: 28px;
    background: radial-gradient(1200px 300px at 20% 0%, rgba(255,255,255,.45), transparent 60%),
                linear-gradient(135deg, rgba(255,107,139,.28), rgba(200,162,200,.22)),
                linear-gradient(135deg, #ffffff, #ffffff);
    border: 1px solid rgba(255,107,139,.18);
    box-shadow: var(--shadow2);
    overflow: hidden;
    position: relative;
}
.premium-hero:before{
    content:'';
    position:absolute;
    right:-120px; top:-120px;
    width:260px; height:260px;
    background: radial-gradient(circle, rgba(255,107,139,.25), transparent 65%);
    border-radius: 999px;
}
.premium-hero:after{
    content:'';
    position:absolute;
    left:-140px; bottom:-140px;
    width:320px; height:320px;
    background: radial-gradient(circle, rgba(200,162,200,.25), transparent 60%);
    border-radius: 999px;
}

.hero-pill{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 700;
    color: var(--pink);
    background: rgba(255,107,139,.12);
    border: 1px solid rgba(255,107,139,.18);
}
.hero-title{
    font-size: 2rem;
    letter-spacing: -0.5px;
}
.hero-sub{
    color: rgba(0,0,0,.55);
    max-width: 520px;
}
.hero-side{
    width: 100%;
    max-width: 360px;
}
.hero-side-card{
    border-radius: 18px;
    padding: 16px;
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    box-shadow: var(--shadow);
}
.hero-icon{
    width: 44px; height: 44px;
    border-radius: 14px;
    display:flex; align-items:center; justify-content:center;
    background: rgba(255,255,255,.18);
    color: #fff;
    font-size: 18px;
}
.btn-ghost{
    border: 2px solid rgba(255,107,139,.35);
    color: var(--pink);
    background: rgba(255,255,255,.65);
    border-radius: 14px;
    padding: 10px 14px;
    font-weight: 600;
}
.btn-ghost:hover{
    background: rgba(255,107,139,.10);
    color: var(--pink);
}

/* ALERT */
.premium-alert{
    border-radius: 18px;
    padding: 16px 18px;
    border: 1px solid rgba(255,107,139,.18);
    background: linear-gradient(135deg, #ffffff, rgba(255,107,139,.06));
    box-shadow: var(--shadow2);
}
.premium-alert-icon{
    width: 46px; height: 46px;
    border-radius: 16px;
    display:flex; align-items:center; justify-content:center;
    background: rgba(255,107,139,.12);
    color: var(--pink);
    font-size: 18px;
}
.badge-premium{
    background: linear-gradient(135deg, var(--pink), #FFD166);
    color: #fff;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 800;
}

/* PLAN CARD */
.plan-card{
    border-radius: 22px;
    border: 1px solid rgba(255,107,139,.16);
    background: #fff;
    box-shadow: var(--shadow2);
    overflow: hidden;
    display:flex;
    flex-direction: column;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    position: relative;
}
.plan-card:hover{
    transform: translateY(-6px);
    box-shadow: var(--shadow);
    border-color: rgba(255,107,139,.35);
}
.plan-card.is-popular{
    border-color: rgba(255,107,139,.55);
    box-shadow: var(--shadow);
}
.plan-badge{
    position:absolute;
    top: 14px;
    right: 14px;
    z-index: 3;
}
.plan-badge .badge{
    background: linear-gradient(135deg, var(--pink), #FFD166);
    color: #fff;
    border-radius: 999px;
    padding: 7px 12px;
    font-weight: 900;
    letter-spacing: .3px;
}
.plan-head{
    padding: 18px 18px 12px 18px;
    background:
        radial-gradient(700px 200px at 30% 0%, rgba(255,107,139,.12), transparent 60%),
        linear-gradient(135deg, #fff, rgba(255,240,245,.55));
}
.plan-mark{
    width: 44px; height: 44px;
    border-radius: 16px;
    display:flex; align-items:center; justify-content:center;
    background: rgba(255,107,139,.12);
    color: var(--pink);
    font-size: 18px;
}
.plan-price .price-number{
    font-size: 1.6rem;
    font-weight: 900;
    color: var(--pink);
    line-height: 1.1;
}
.plan-body{
    padding: 10px 18px 0 18px;
}
.feature-list{
    list-style: none;
    padding: 0;
    margin: 0;
    display:flex;
    flex-direction: column;
    gap: 10px;
}
.feature-list li{
    display:flex;
    align-items:flex-start;
    gap: 10px;
    color: rgba(0,0,0,.72);
}
.feature-dot{
    width: 22px; height: 22px;
    border-radius: 999px;
    display:flex; align-items:center; justify-content:center;
    background: rgba(152,255,152,.25);
    color: #1f8a3b;
    flex-shrink: 0;
    margin-top: 2px;
    font-size: 12px;
}
.plan-foot{
    padding: 16px 18px 18px 18px;
}

/* BUTTONS */
.btn-premium{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    border: none;
    border-radius: 14px;
    padding: 12px 14px;
    color: #fff;
    font-weight: 800;
}
.btn-premium:hover{
    filter: brightness(.96);
    color:#fff;
}
.btn-outline-premium{
    background: transparent;
    border: 2px solid rgba(255,107,139,.45);
    border-radius: 14px;
    padding: 12px 14px;
    color: var(--pink);
    font-weight: 800;
}
.btn-outline-premium:hover{
    background: rgba(255,107,139,.10);
    color: var(--pink);
}
.btn-active{
    background: rgba(25,135,84,.12);
    border: 2px solid rgba(25,135,84,.35);
    color: #198754;
    border-radius: 14px;
    padding: 12px 14px;
    font-weight: 900;
}

/* BENEFITS */
.benefit-card{
    border-radius: 22px;
    padding: 22px;
    background: radial-gradient(900px 260px at 20% 0%, rgba(255,107,139,.10), transparent 60%),
                linear-gradient(135deg, #ffffff, rgba(240,248,255,.55));
    border: 1px solid rgba(255,107,139,.14);
    box-shadow: var(--shadow2);
}
.benefit-item{
    border-radius: 18px;
    padding: 14px;
    background: rgba(255,255,255,.75);
    border: 1px solid rgba(0,0,0,.06);
    display:flex;
    gap: 12px;
    align-items:flex-start;
}
.benefit-icon{
    width: 46px; height: 46px;
    border-radius: 16px;
    display:flex; align-items:center; justify-content:center;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 12px 24px rgba(0,0,0,.10);
}
.i-gold{ background: linear-gradient(135deg, #FFD166, #FF6B8B); }
.i-sky{ background: linear-gradient(135deg, #87CEEB, #6C63FF); }
.i-mint{ background: linear-gradient(135deg, #98FF98, #2ECC71); }
.i-purple{ background: linear-gradient(135deg, #C8A2C8, #FF6B8B); }

/* RESPONSIVE: jangan scale paksa */
@media (max-width: 991.98px){
    .hero-title{ font-size: 1.6rem; }
}
</style>
@endsection
