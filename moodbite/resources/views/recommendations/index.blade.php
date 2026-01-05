@extends('layouts.app')

@section('title', 'Cari Makanan - MoodBite')

@section('content')
@php
    $user = auth()->user();
    $isPremium = $user ? $user->isPremium() : false;

    // mood free user (6)
    $freeMoods = [
        'happy' => ['icon' => 'fa-smile', 'label' => 'Bahagia', 'desc' => 'Mood cerah & fun', 'color' => '#FFD166'],
        'sad' => ['icon' => 'fa-sad-tear', 'label' => 'Sedih', 'desc' => 'Butuh comfort food', 'color' => '#87CEEB'],
        'energetic' => ['icon' => 'fa-bolt', 'label' => 'Berenergi', 'desc' => 'Boost energi kamu', 'color' => '#98FF98'],
        'stress' => ['icon' => 'fa-wind', 'label' => 'Stress', 'desc' => 'Cari yang calming', 'color' => '#C8A2C8'],
        'romantic' => ['icon' => 'fa-heart', 'label' => 'Romantis', 'desc' => 'Sweet & lovely', 'color' => '#FF9AA2'],
        'hungry' => ['icon' => 'fa-hamburger', 'label' => 'Lapar', 'desc' => 'Butuh makan berat', 'color' => '#FFB347'],
    ];

    // mood premium tambahan (6)
    $premiumMoods = [
        'anxious' => ['icon' => 'fa-face-frown-open', 'label' => 'Anxious', 'desc' => 'Butuh calming food', 'color' => '#A0C4FF'],
        'lazy' => ['icon' => 'fa-bed', 'label' => 'Lazy', 'desc' => 'Mager? pilih yang simple', 'color' => '#BDB2FF'],
        'rainy' => ['icon' => 'fa-cloud-rain', 'label' => 'Rainy', 'desc' => 'Enaknya yang hangat', 'color' => '#9BF6FF'],
        'focus' => ['icon' => 'fa-bullseye', 'label' => 'Focus', 'desc' => 'Biar makin produktif', 'color' => '#CAFFBF'],
        'sleepy' => ['icon' => 'fa-moon', 'label' => 'Sleepy', 'desc' => 'Makanan ringan & nyaman', 'color' => '#FFC6FF'],
        'party' => ['icon' => 'fa-champagne-glasses', 'label' => 'Party', 'desc' => 'Vibes seru & ramai', 'color' => '#FFD6A5'],
    ];
@endphp

<div class="mood-wrap">

    <div class="container">

        {{-- ✅ HERO HEADER --}}
        <div class="mood-hero mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <span class="hero-pill">
                        <i class="fas fa-sparkles me-2"></i>MoodBite Recommendations
                    </span>
                    <h2 class="fw-bold text-white mt-3 mb-1">
                        Cari Makanan Sesuai Mood ✨
                    </h2>
                    <p class="text-white-50 mb-0">
                        Pilih suasana hati kamu dan MoodBite bakal bantu cariin makanan yang paling cocok.
                    </p>
                </div>

                {{-- ✅ BUTTON PREMIUM --}}
                @if(!$isPremium)
                    <a href="/premium" class="btn btn-upgrade">
                        <i class="fas fa-gem me-2"></i>Upgrade Premium
                    </a>
                @else
                    <span class="premium-pill">
                        <i class="fas fa-crown me-2"></i>Premium Activated
                    </span>
                @endif
            </div>
        </div>

        {{-- ✅ MOOD PICKER --}}
        <div class="mood-card-shell">

            <h5 class="fw-bold mb-1 text-dark">
                <i class="fas fa-face-smile me-2"></i>Bagaimana perasaanmu hari ini?
            </h5>
            <p class="text-muted mb-4">
                Pilih mood di bawah untuk mendapatkan rekomendasi makanan yang cocok.
            </p>

            {{-- ✅ FORM --}}
            <form action="{{ route('recommendations') }}" method="POST">
                @csrf

                {{-- ✅ FREE MOODS --}}
                <div class="section-label">
                    <i class="fas fa-fire me-2"></i>Mood Gratis
                </div>

                <div class="row g-3">
                    @foreach($freeMoods as $key => $m)
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="mood" id="mood-{{ $key }}" value="{{ $key }}" required>
                            <label class="mood-box" for="mood-{{ $key }}" style="--moodColor: {{ $m['color'] }}">
                                <div class="mood-icon" style="background: {{ $m['color'] }}">
                                    <i class="fas {{ $m['icon'] }}"></i>
                                </div>
                                <div class="mood-info">
                                    <div class="mood-title">{{ $m['label'] }}</div>
                                    <div class="mood-desc">{{ $m['desc'] }}</div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                {{-- ✅ PREMIUM MOODS --}}
                <div class="section-label mt-5">
                    <i class="fas fa-crown me-2"></i>Mood Premium
                </div>

                <div class="row g-3">
                    @foreach($premiumMoods as $key => $m)
                        <div class="col-md-4">
                            <input type="radio"
                                   class="btn-check"
                                   name="mood"
                                   id="mood-{{ $key }}"
                                   value="{{ $key }}"
                                   {{ !$isPremium ? 'disabled' : '' }}>

                            <label class="mood-box premium-lock {{ !$isPremium ? 'locked' : '' }}"
                                   for="mood-{{ $key }}"
                                   style="--moodColor: {{ $m['color'] }}">
                                <div class="mood-icon" style="background: {{ $m['color'] }}">
                                    <i class="fas {{ $m['icon'] }}"></i>
                                </div>
                                <div class="mood-info">
                                    <div class="mood-title">{{ $m['label'] }}</div>
                                    <div class="mood-desc">{{ $m['desc'] }}</div>
                                </div>

                                @if(!$isPremium)
                                    <div class="lock-overlay">
                                        <span>
                                            <i class="fas fa-lock me-2"></i>Premium Only
                                        </span>
                                    </div>
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>

                @error('mood')
                    <div class="text-danger mt-3">{{ $message }}</div>
                @enderror

                {{-- ✅ SUBMIT --}}
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-search px-5 py-3">
                        <i class="fas fa-search me-2"></i>Cari Rekomendasi
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<style>
:root{
    --pink:#FF6B8B;
    --purple:#C8A2C8;
    --glass: rgba(255,255,255,.75);
    --shadow: 0 18px 55px rgba(0,0,0,.12);
}

/* ✅ BACKGROUND ADMIN DASHBOARD */
.mood-wrap{
    min-height: 88vh;
    padding: 60px 0;
    background:
        radial-gradient(circle at 15% 15%, rgba(255,107,139,.22), transparent 42%),
        radial-gradient(circle at 85% 20%, rgba(135,206,235,.20), transparent 45%),
        radial-gradient(circle at 55% 92%, rgba(200,162,200,.20), transparent 42%),
        linear-gradient(135deg, #FFF4F9 0%, #F7FBFF 100%);
    position: relative;
    overflow: hidden;
}
.mood-wrap::before{
    content:"";
    position:absolute;
    inset:0;
    background-image: radial-gradient(rgba(255,255,255,.6) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity:.16;
    pointer-events:none;
}

/* ✅ HERO */
.mood-hero{
    border-radius: 28px;
    padding: 30px;
    background: linear-gradient(135deg, rgba(255,107,139,.95), rgba(200,162,200,.95));
    border: 1px solid rgba(255,255,255,.28);
    box-shadow: 0 25px 65px rgba(255,107,139,.25);
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

/* ✅ UPGRADE BUTTON */
.btn-upgrade{
    padding: 10px 16px;
    border-radius: 999px;
    font-weight: 800;
    border: 2px solid rgba(255,255,255,.75);
    background: rgba(255,255,255,.12);
    color: #fff;
    text-decoration: none;
}
.btn-upgrade:hover{
    background: rgba(255,255,255,.2);
    color:#fff;
}

.premium-pill{
    padding: 10px 16px;
    border-radius: 999px;
    font-weight: 900;
    background: rgba(255,255,255,.92);
    color: var(--pink);
}

/* ✅ GLASS CARD */
.mood-card-shell{
    margin-top: 18px;
    border-radius: 28px;
    background: rgba(255,255,255,.82);
    border: 1px solid rgba(255,255,255,.65);
    backdrop-filter: blur(16px);
    padding: 28px;
    box-shadow: var(--shadow);
}

/* ✅ SECTION LABEL */
.section-label{
    font-weight: 900;
    font-size: 14px;
    margin-bottom: 14px;
    color: #444;
    display:flex;
    align-items:center;
}

/* ✅ MOOD BOX */
.mood-box{
    display:flex;
    align-items:center;
    gap: 14px;
    border-radius: 22px;
    padding: 18px;
    background: rgba(255,255,255,.95);
    border: 1px solid rgba(0,0,0,.06);
    box-shadow: 0 12px 30px rgba(0,0,0,.06);
    cursor:pointer;
    transition:.25s ease;
    position:relative;
    overflow:hidden;
}
.mood-box:hover{
    transform: translateY(-6px);
    box-shadow: 0 25px 55px rgba(0,0,0,.11);
}
.btn-check:checked + .mood-box{
    border: 2px solid var(--moodColor);
    box-shadow: 0 20px 40px rgba(0,0,0,.12);
}

/* ICON */
.mood-icon{
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size: 20px;
    flex-shrink:0;
}
.mood-title{
    font-weight: 900;
    font-size: 16px;
    color:#333;
}
.mood-desc{
    font-size: 13px;
    color:#777;
}

/* ✅ PREMIUM LOCK */
.premium-lock.locked{
    filter: grayscale(.2);
    opacity: .8;
    cursor:not-allowed;
}
.lock-overlay{
    position:absolute;
    inset:0;
    background: rgba(255,255,255,.55);
    backdrop-filter: blur(5px);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight: 900;
    color: #444;
}

/* ✅ BUTTON SEARCH */
.btn-search{
    border:none;
    border-radius: 999px;
    font-weight: 900;
    font-size: 16px;
    color:#fff;
    background: linear-gradient(135deg, var(--pink), #FFD166);
    box-shadow: 0 20px 45px rgba(255,107,139,.25);
    transition:.2s ease;
}
.btn-search:hover{
    transform: translateY(-3px);
    filter: brightness(.96);
    color:#fff;
}
</style>
@endsection
