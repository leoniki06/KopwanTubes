@extends('layouts.app')

@section('title', 'Dashboard - MoodBite')

@section('content')

@php
    // harga membership monthly (biar konsisten dengan MembershipController)
    $monthlyPrice = 30000;

    $icon = [
        'happy' => 'fa-smile',
        'sad' => 'fa-sad-tear',
        'energetic' => 'fa-bolt',
        'stress' => 'fa-wind'
    ];

    $moodsPopular = [
        'happy' => ['label' => '😊 Bahagia', 'percentage' => '85%', 'color' => '#FF6B8B'],
        'sad' => ['label' => '😢 Sedih', 'percentage' => '72%', 'color' => '#4A90E2'],
        'energetic' => ['label' => '⚡ Berenergi', 'percentage' => '68%', 'color' => '#FF9F43'],
        'stress' => ['label' => '😫 Stress', 'percentage' => '55%', 'color' => '#6C5CE7'],
    ];
@endphp

<div class="container py-4">

    {{-- ================== WELCOME HERO ================== --}}
    <div class="welcome-card mb-4">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-4">

            <div class="text-white">
                <h1 class="welcome-title mb-2">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="welcome-sub mb-0">
                    Bagaimana suasana hatimu hari ini?
                </p>

                @if($isUserPremium)
                    <div class="mt-3">
                        <span class="badge badge-premium">
                            <i class="fas fa-crown me-2"></i> PREMIUM MEMBER
                            <small class="ms-2 opacity-75">Aktif {{ $stats['premium_days_left'] }} hari lagi</small>
                        </span>
                    </div>
                @endif
            </div>

            <div class="d-flex gap-2 flex-wrap">
                @if($isUserPremium)
                    <a href="{{ route('premium.recipes.index') }}" class="btn btn-hero-light">
                        <i class="fas fa-gem me-2"></i> Resep Eksklusif
                    </a>
                @endif
                <a href="{{ route('recommendations') }}" class="btn btn-hero-outline">
                    <i class="fas fa-utensils me-2"></i> Cari Makanan
                </a>
            </div>

        </div>
    </div>

    {{-- ================== SECTION RESEP EKSKLUSIF PREMIUM ================== --}}
    @if($isUserPremium && $premiumExclusiveRecipes->count() > 0)
        <div class="card card-soft border-0 mb-4">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-pink mb-1">
                            <i class="fas fa-gem me-2"></i>Resep Eksklusif dari Chef Ternama
                        </h5>
                        <p class="text-muted small mb-0">Khusus member premium</p>
                    </div>
                    <a href="{{ route('premium.recipes.index') }}" class="btn btn-outline-pink btn-sm">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-3">
                    @foreach($premiumExclusiveRecipes as $recipe)
                        <div class="col-md-4">
                            <div class="recipe-mini-card h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-pink-soft text-pink">
                                        <i class="fas fa-star me-1"></i> PREMIUM
                                    </span>
                                    <small class="text-muted">
                                        <i class="fas fa-user-tie me-1"></i> {{ $recipe->chef_name }}
                                    </small>
                                </div>

                                <h6 class="fw-bold mb-2">{{ $recipe->recipe_name }}</h6>

                                <p class="text-muted small mb-3">
                                    {{ Str::limit($recipe->description, 80) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-muted small">
                                        <i class="fas fa-clock me-1"></i> {{ $recipe->cooking_time }}m
                                        <span class="ms-2"><i class="fas fa-users me-1"></i> {{ $recipe->servings }}</span>
                                    </div>
                                    <span class="badge bg-light text-dark">{{ ucfirst($recipe->difficulty) }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    @if($recipe->mood_category)
                                        <span class="badge bg-pink-light text-pink">
                                            <i class="fas fa-smile me-1"></i> {{ ucfirst($recipe->mood_category) }}
                                        </span>
                                    @endif

                                    <a href="{{ route('premium.recipes.show', $recipe->id) }}" class="btn btn-pink btn-sm">
                                        <i class="fas fa-book-open me-1"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    @endif


    {{-- ================== PROMO NON-PREMIUM (HIGHLIGHT GLOW) ================== --}}
    @if(!$isUserPremium && $exclusivePreview)
        <div class="promo-highlight mb-4">
            <div class="promo-highlight-inner">
                <div class="row align-items-center g-4">

                    <div class="col-lg-8">
                        <div class="promo-head">
                            <div class="promo-icon-glow">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1 text-pink">Upgrade ke Premium ✨</h4>
                                <p class="text-muted small mb-0">
                                    Akses resep eksklusif dari chef ternama + fitur premium lainnya
                                </p>
                            </div>
                        </div>

                        <div class="row g-2 mt-3">
                            @foreach($exclusivePreview as $preview)
                                <div class="col-md-6">
                                    <div class="promo-recipe highlight-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $preview->recipe_name }}</h6>
                                                <small class="text-muted">by {{ $preview->chef_name }}</small>
                                            </div>
                                            <span class="badge bg-pink text-white">PREMIUM</span>
                                        </div>

                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-smile me-1"></i>{{ ucfirst($preview->mood_category) }}
                                            </span>
                                            <span class="badge bg-light text-dark">{{ ucfirst($preview->difficulty) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="promo-price premium-box">
                            <div class="price-number">
                                Rp {{ number_format($monthlyPrice, 0, ',', '.') }}
                            </div>
                            <div class="text-muted small">/bulan</div>

                            <a href="{{ route('membership.index') }}" class="btn btn-premium-glow w-100 mt-3">
                                <i class="fas fa-crown me-2"></i> Upgrade Sekarang
                            </a>

                            <div class="small text-muted mt-3">
                                ✅ 7 hari garansi uang kembali <br>
                                ✅ Bisa batal kapan saja
                            </div>

                            <div class="flash-badge">
                                HOT DEAL 🔥
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- ================== MAIN CONTENT ================== --}}
    <div class="row g-4">

        {{-- Mood Hari Ini --}}
        <div class="col-lg-6">
            <div class="card card-soft border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-pink mb-2">
                        <i class="fas fa-heart me-2"></i>Mood Hari Ini
                    </h5>
                    <p class="text-muted small mb-3">
                        Pilih mood kamu untuk mendapatkan rekomendasi makanan:
                    </p>

                    <div class="row g-2">
                        @foreach(['happy', 'sad', 'energetic', 'stress'] as $mood)
                            <div class="col-6">
                                <a href="{{ route('recommendations') }}?mood={{ $mood }}" class="btn btn-outline-pink w-100">
                                    <i class="fas {{ $icon[$mood] }} me-2"></i>{{ ucfirst($mood) }}
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @if($isUserPremium)
                        <hr class="my-4">

                        <h6 class="fw-bold text-pink mb-3">
                            Statistik Premium Anda
                        </h6>

                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <div class="stat-box">
                                    <div class="stat-number">{{ $stats['premium_days_left'] }}</div>
                                    <small class="text-muted">Hari Tersisa</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-box">
                                    <div class="stat-number">{{ $stats['total_exclusive_recipes'] }}</div>
                                    <small class="text-muted">Resep Eksklusif</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-box">
                                    <div class="stat-number">{{ $stats['total_premium_food'] }}</div>
                                    <small class="text-muted">Makanan Premium</small>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Mood Populer --}}
        <div class="col-lg-6">
            <div class="card card-soft border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-pink mb-3">
                        <i class="fas fa-fire me-2"></i>Mood Populer
                    </h5>

                    @foreach($moodsPopular as $mood => $data)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small">{{ $data['label'] }}</span>
                                <span class="fw-bold" style="color:{{ $data['color'] }}">
                                    {{ $data['percentage'] }}
                                </span>
                            </div>

                            <div class="progress" style="height:8px;">
                                <div class="progress-bar"
                                     style="width:{{ $data['percentage'] }}; background:{{ $data['color'] }};">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($freeRecommendations->count() > 0)
                        <hr class="my-4">

                        <h6 class="fw-bold text-pink mb-3">
                            Rekomendasi Free untuk Anda
                        </h6>

                        <div class="row g-2">
                            @foreach($freeRecommendations->take(2) as $food)
                                <div class="col-6">
                                    <div class="food-mini-card">
                                        <h6 class="fw-bold mb-1">{{ $food->name }}</h6>
                                        <p class="text-muted small mb-2">{{ Str::limit($food->description, 40) }}</p>
                                        <span class="badge bg-pink text-white">
                                            {{ ucfirst($food->mood) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="card card-soft border-0 mt-4">
        <div class="card-body text-center p-4">
            <h5 class="fw-bold text-pink mb-2">Mulai Petualangan Makananmu!</h5>
            <p class="text-muted mb-4">Temukan makanan yang sempurna untuk setiap suasana hati.</p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('recommendations') }}" class="btn btn-pink px-4">
                    <i class="fas fa-search me-2"></i>Cari Rekomendasi
                </a>

                @if(!$isUserPremium)
                    <a href="{{ route('membership.index') }}" class="btn btn-outline-pink px-4">
                        <i class="fas fa-crown me-2"></i>Upgrade Premium
                    </a>
                @else
                    <a href="{{ route('premium.recipes.index') }}" class="btn btn-outline-pink px-4">
                        <i class="fas fa-gem me-2"></i>Resep Eksklusif
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>


{{-- ================== STYLE ================== --}}
<style>
:root{
    --pink:#FF6B8B;
    --pink2:#C8A2C8;
    --pink-soft:#FFF0F5;
}

/* General */
.text-pink{ color: var(--pink) !important; }
.bg-pink-soft{ background: var(--pink-soft) !important; }
.bg-pink-light{ background: rgba(255,107,139,.10); }

/* Welcome Hero */
.welcome-card{
    border-radius: 22px;
    padding: 26px;
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    box-shadow: 0 18px 45px rgba(255,107,139,.22);
}
.welcome-title{ font-size: 2rem; font-weight: 900; letter-spacing: -.5px; }
.welcome-sub{ opacity: .85; }

.badge-premium{
    background: rgba(255,255,255,.90);
    color: var(--pink);
    padding: 10px 14px;
    border-radius: 999px;
    font-weight: 900;
}

/* Buttons */
.btn-pink{
    background: var(--pink);
    color:#fff;
    font-weight: 900;
    border-radius: 14px;
    padding: 10px 16px;
}
.btn-pink:hover{ filter: brightness(.95); color:#fff; }

.btn-outline-pink{
    border: 2px solid rgba(255,107,139,.50);
    color: var(--pink);
    font-weight: 900;
    border-radius: 14px;
    padding: 10px 16px;
    background: transparent;
}
.btn-outline-pink:hover{
    background: rgba(255,107,139,.10);
    color: var(--pink);
}

.btn-hero-light{
    background: rgba(255,255,255,.92);
    color: var(--pink);
    font-weight: 900;
    border-radius: 14px;
    padding: 10px 16px;
}
.btn-hero-light:hover{ filter: brightness(.98); color: var(--pink); }

.btn-hero-outline{
    border: 2px solid rgba(255,255,255,.85);
    color: #fff;
    font-weight: 900;
    border-radius: 14px;
    padding: 10px 16px;
    background: transparent;
}
.btn-hero-outline:hover{
    background: rgba(255,255,255,.12);
    color: #fff;
}

/* Card soft */
.card-soft{
    background: rgba(255,255,255,.95);
    border-radius: 22px;
    box-shadow: 0 12px 35px rgba(0,0,0,.08);
}

/* Mini recipe card */
.recipe-mini-card{
    background:#fff;
    border-radius: 18px;
    padding: 18px;
    border: 1px solid rgba(255,107,139,.12);
    box-shadow: 0 12px 28px rgba(255,107,139,.08);
    transition: .25s;
}
.recipe-mini-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 20px 50px rgba(255,107,139,.16);
}

/* Stat box */
.stat-box{
    border-radius: 18px;
    padding: 14px;
    border: 1px solid rgba(255,107,139,.14);
    background: rgba(255,107,139,.06);
}
.stat-number{
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--pink);
}

/* Food mini card */
.food-mini-card{
    background: rgba(255,255,255,.90);
    border-radius: 16px;
    padding: 14px;
    border: 1px solid rgba(0,0,0,.06);
}

/* ============================
   PREMIUM PROMO HIGHLIGHT
============================ */
.promo-highlight{
    position: relative;
    border-radius: 26px;
    padding: 3px;
    background: linear-gradient(135deg,
        rgba(255,107,139,.8),
        rgba(255,209,102,.6),
        rgba(200,162,200,.75)
    );
    box-shadow: 0 24px 60px rgba(255,107,139,.20);
    overflow: hidden;
    animation: floatGlow 5s ease-in-out infinite;
}

.promo-highlight-inner{
    background: rgba(255,255,255,.95);
    border-radius: 24px;
    padding: 24px;
    position: relative;
    overflow:hidden;
}

.promo-highlight-inner::after{
    content:"";
    position:absolute;
    inset:-40%;
    background: linear-gradient(120deg,
        transparent,
        rgba(255,255,255,.35),
        transparent
    );
    transform: rotate(25deg);
    animation: shimmer 6s infinite;
}

@keyframes shimmer{
    0%{ transform: translateX(-60%) rotate(25deg); opacity:0; }
    25%{ opacity:1; }
    50%{ transform: translateX(60%) rotate(25deg); opacity:0; }
    100%{ opacity:0; }
}

@keyframes floatGlow{
    0%,100%{ transform: translateY(0px); }
    50%{ transform: translateY(-4px); }
}

.promo-head{
    display:flex;
    gap: 14px;
    align-items:center;
}

.promo-icon-glow{
    width: 52px;
    height: 52px;
    border-radius: 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size: 18px;
    background: linear-gradient(135deg, var(--pink), #FFD166);
    box-shadow: 0 14px 30px rgba(255,107,139,.35);
}

.promo-recipe{
    border-radius: 18px;
    padding: 14px;
    background: rgba(255,255,255,.92);
}

.highlight-card{
    border: 1px solid rgba(255,107,139,.12);
    box-shadow: 0 8px 22px rgba(255,107,139,.08);
    transition: .2s ease;
}
.highlight-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 14px 38px rgba(255,107,139,.18);
}

.premium-box{
    position: relative;
    border-radius: 22px;
    padding: 20px;
    background: #fff;
    border: 1px solid rgba(255,107,139,.15);
    box-shadow: 0 18px 40px rgba(255,107,139,.14);
    transition: .25s ease;
}
.premium-box:hover{
    transform: translateY(-5px);
    box-shadow: 0 22px 60px rgba(255,107,139,.25);
}

.price-number{
    font-size: 2.3rem;
    font-weight: 900;
    color: var(--pink);
    line-height: 1;
}

.btn-premium-glow{
    background: linear-gradient(135deg, var(--pink), #FFD166);
    border: none;
    color:#fff;
    font-weight: 900;
    padding: 12px 16px;
    border-radius: 16px;
    transition: .25s ease;
    box-shadow: 0 16px 40px rgba(255,107,139,.25);
}
.btn-premium-glow:hover{
    transform: translateY(-2px);
    box-shadow: 0 22px 60px rgba(255,107,139,.35);
    filter: brightness(1.02);
    color:#fff;
}

.flash-badge{
    position:absolute;
    top: 14px;
    right: 14px;
    background: linear-gradient(135deg, #FF6B8B, #FF9F43);
    color:#fff;
    font-weight: 900;
    font-size: 11px;
    padding: 7px 12px;
    border-radius: 999px;
    box-shadow: 0 12px 30px rgba(255,107,139,.32);
    animation: pulseBadge 1.7s infinite;
}
@keyframes pulseBadge{
    0%,100%{ transform: scale(1); opacity: 1; }
    50%{ transform: scale(1.08); opacity: .9; }
}

@media (max-width: 768px){
    .welcome-title{ font-size: 1.6rem; }
}
</style>

@endsection
