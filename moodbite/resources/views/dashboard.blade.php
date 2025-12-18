@extends('layouts.app')

@section('title', 'Dashboard - MoodBite')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card p-4 rounded-3" style="background: linear-gradient(135deg, #FF6B8B, #C8A2C8);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="text-white mb-2">Halo, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-white mb-0">Bagaimana suasana hatimu hari ini?</p>
                    
                    {{-- Tampilkan status premium --}}
                    @if($isUserPremium)
                        <div class="mt-3">
                            <span class="badge bg-white text-pink px-3 py-2">
                                <i class="fas fa-crown me-2"></i>PREMIUM MEMBER
                                <small class="ms-2 text-muted">Aktif {{ $stats['premium_days_left'] }} hari lagi</small>
                            </span>
                        </div>
                    @endif
                </div>
                <div class="col-md-4 text-md-end">
                    @if($isUserPremium)
                        <a href="{{ route('premium.recipes.index') }}" class="btn btn-light btn-lg me-2">
                            <i class="fas fa-crown me-2"></i>Resep Eksklusif
                        </a>
                    @endif
                    <a href="{{ route('recommendations') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-utensils me-2"></i>Cari Makanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========== SECTION RESEP EKSKLUSIF (HANYA UNTUK PREMIUM) ========== --}}
@if($isUserPremium && $premiumExclusiveRecipes->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1 text-pink fw-bold">
                            <i class="fas fa-gem me-2"></i>Resep Eksklusif dari Chef Ternama
                        </h5>
                        <p class="text-muted small mb-0">Khusus member premium</p>
                    </div>
                    <a href="{{ route('premium.recipes.index') }}" class="btn btn-outline-pink btn-sm">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row g-3">
                    @foreach($premiumExclusiveRecipes as $recipe)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm-hover">
                            <div class="card-body">
                                <!-- Badge & Chef -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-pink-soft text-pink">
                                        <i class="fas fa-star me-1"></i> PREMIUM
                                    </span>
                                    <small class="text-muted">
                                        <i class="fas fa-user-tie me-1"></i> {{ $recipe->chef_name }}
                                    </small>
                                </div>
                                
                                <!-- Recipe Name -->
                                <h6 class="fw-bold mb-2">{{ $recipe->recipe_name }}</h6>
                                
                                <!-- Description -->
                                <p class="text-muted small mb-3">
                                    {{ Str::limit($recipe->description, 80) }}
                                </p>
                                
                                <!-- Stats -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="text-muted small me-3">
                                            <i class="fas fa-clock me-1"></i> {{ $recipe->cooking_time }}m
                                        </span>
                                        <span class="text-muted small">
                                            <i class="fas fa-users me-1"></i> {{ $recipe->servings }} porsi
                                        </span>
                                    </div>
                                    <span class="badge bg-light text-dark">
                                        {{ $recipe->difficulty }}
                                    </span>
                                </div>
                                
                                <!-- Mood & Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($recipe->mood_category)
                                        <span class="badge bg-pink-light">
                                            <i class="fas fa-smile me-1"></i> {{ ucfirst($recipe->mood_category) }}
                                        </span>
                                    @endif
                                    <a href="{{ route('premium.recipes.show', $recipe->id) }}" 
                                       class="btn btn-pink btn-sm">
                                        <i class="fas fa-book-open me-1"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ========== SECTION MAKANAN PREMIUM ========== --}}
@if($isUserPremium && $premiumFoodRecommendations->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 text-pink fw-bold">
                    <i class="fas fa-star me-2"></i>Makanan Premium untuk Anda
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($premiumFoodRecommendations as $food)
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-pink-soft">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title fw-bold mb-0">{{ $food->name }}</h6>
                                    <i class="fas fa-crown text-pink"></i>
                                </div>
                                <p class="text-muted small mb-2">{{ Str::limit($food->description, 50) }}</p>
                                <span class="badge bg-white text-pink">
                                    {{ ucfirst($food->mood) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ========== SECTION PROMO UNTUK NON-PREMIUM ========== --}}
@if(!$isUserPremium && $exclusivePreview)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 bg-pink-soft">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-pink text-white rounded-circle p-2 me-3">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 text-pink">Upgrade ke Premium</h5>
                                <p class="text-muted small mb-0">Akses resep eksklusif dari chef ternama</p>
                            </div>
                        </div>
                        
                        <div class="row g-2">
                            @foreach($exclusivePreview as $preview)
                            <div class="col-md-6">
                                <div class="card bg-white border-0">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $preview->recipe_name }}</h6>
                                                <small class="text-muted">by {{ $preview->chef_name }}</small>
                                            </div>
                                            <span class="badge bg-pink text-white">PREMIUM</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-smile me-1"></i> {{ ucfirst($preview->mood_category) }}
                                            </span>
                                            <span class="badge bg-light text-dark">{{ $preview->difficulty }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="text-center">
                            <div class="mb-3">
                                <h4 class="text-pink mb-1">Rp 49.999</h4>
                                <small class="text-muted">/bulan</small>
                            </div>
                            <a href="{{ route('membership.index') }}" class="btn btn-pink w-100 mb-2">
                                <i class="fas fa-crown me-2"></i> Upgrade Sekarang
                            </a>
                            <p class="text-muted small mb-0">
                                ✅ 7 hari garansi uang kembali<br>
                                ✅ Batal kapan saja
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ========== MAIN CONTENT ========== --}}
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-pink fw-bold mb-3">
                    <i class="fas fa-heart me-2"></i>Mood Hari Ini
                </h5>
                <p class="text-muted small mb-3">Pilih mood kamu untuk mendapatkan rekomendasi makanan:</p>
                
                <div class="row g-2 mb-4">
                    @foreach(['happy', 'sad', 'energetic', 'stress'] as $mood)
                    <div class="col-6">
                        <a href="{{ route('recommendations') }}?mood={{ $mood }}" 
                           class="btn btn-outline-pink w-100">
                            @php
                                $icon = [
                                    'happy' => 'fa-smile',
                                    'sad' => 'fa-sad-tear',
                                    'energetic' => 'fa-bolt',
                                    'stress' => 'fa-wind'
                                ];
                            @endphp
                            <i class="fas {{ $icon[$mood] }} me-2"></i>{{ ucfirst($mood) }}
                        </a>
                    </div>
                    @endforeach
                </div>
                
                {{-- Statistik Premium --}}
                @if($isUserPremium)
                <hr class="my-4">
                <h6 class="text-pink fw-bold mb-3">Statistik Premium Anda</h6>
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="text-pink fw-bold fs-4">{{ $stats['premium_days_left'] }}</div>
                            <small class="text-muted">Hari Tersisa</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="text-pink fw-bold fs-4">{{ $stats['total_exclusive_recipes'] }}</div>
                            <small class="text-muted">Resep Eksklusif</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="text-pink fw-bold fs-4">{{ $stats['total_premium_food'] }}</div>
                            <small class="text-muted">Makanan Premium</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
 {{-- ========== MOOD POPULER SECTION ========== --}}
<div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
            <h5 class="card-title text-pink fw-bold mb-3">
                <i class="fas fa-fire me-2"></i>Mood Populer
            </h5>
            
            <div class="mb-4">
                @php
                    $moods = [
                        'happy' => [
                            'label' => '😊 Bahagia', 
                            'percentage' => '85%',
                            'color' => '#FF6B8B',
                        ],
                        'sad' => [
                            'label' => '😢 Sedih', 
                            'percentage' => '72%',
                            'color' => '#4A90E2'
                        ],
                        'energetic' => [
                            'label' => '⚡ Berenergi', 
                            'percentage' => '68%',
                            'color' => '#FF9F43',
                        ],
                        'stress' => [
                            'label' => '😫 Stress', 
                            'percentage' => '55%',
                            'color' => '#6C5CE7',
                        ]
                    ];
                @endphp
                
                @foreach($moods as $mood => $data)
                <div class="mood-popular-item mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <span class="text-muted">{{ $data['label'] }}</span>
                        </div>
                        <span class="fw-bold" style="color: {{ $data['color'] }};">{{ $data['percentage'] }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" 
                             style="width: {{ $data['percentage'] }}; 
                                    background-color: {{ $data['color'] }};
                                    border-radius: 4px;">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
                
                {{-- Rekomendasi Free --}}
                @if($freeRecommendations->count() > 0)
                <hr class="my-4">
                <h6 class="text-pink fw-bold mb-3">Rekomendasi Free untuk Anda</h6>
                <div class="row g-2">
                    @foreach($freeRecommendations->take(2) as $food)
                    <div class="col-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-1">{{ $food->name }}</h6>
                                <p class="text-muted small mb-2">{{ Str::limit($food->description, 40) }}</p>
                                <span class="badge bg-pink text-white">
                                    {{ ucfirst($food->mood) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ========== CTA SECTION ========== --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 bg-pink-soft">
            <div class="card-body text-center p-4">
                <h5 class="text-pink fw-bold mb-3">Mulai Petualangan Makananmu!</h5>
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
                            <i class="fas fa-crown me-2"></i>Resep Eksklusif
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== VARIABLES ===== */
:root {
    --pink: #FF6B8B;
    --pink-light: #FFACC7;
    --pink-soft: #FFF0F5;
}

/* ===== TEXT COLORS ===== */
.text-pink { color: var(--pink) !important; }

/* ===== BACKGROUND COLORS ===== */
.bg-pink { background-color: var(--pink) !important; }
.bg-pink-light { background-color: var(--pink-light) !important; }
.bg-pink-soft { background-color: var(--pink-soft) !important; }

/* ===== BUTTONS ===== */
.btn-pink {
    background-color: var(--pink);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-pink:hover {
    background-color: #E05575;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-pink {
    color: var(--pink);
    border: 2px solid var(--pink);
    background: transparent;
    padding: 8px 20px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-outline-pink:hover {
    background-color: var(--pink);
    color: white;
}

/* ===== BADGES ===== */
.badge.bg-pink-soft {
    background-color: var(--pink-soft);
    color: var(--pink);
    font-weight: 500;
    padding: 5px 12px;
}

.badge.bg-pink-light {
    background-color: var(--pink-light);
    color: var(--pink);
    font-weight: 500;
    padding: 5px 12px;
}

.badge.bg-pink {
    background-color: var(--pink);
    color: white;
    font-weight: 500;
    padding: 5px 12px;
}

/* ===== CARDS ===== */
.card.border-0 {
    border-radius: 10px;
}

.shadow-sm-hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s;
}

.shadow-sm-hover:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

/* ===== PROGRESS BAR ===== */
.progress-bar.bg-pink {
    background-color: var(--pink) !important;
}

/* ===== WELCOME CARD ===== */
.welcome-card {
    box-shadow: 0 10px 30px rgba(255, 107, 139, 0.2);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .welcome-card .btn-lg {
        padding: 10px 20px;
        font-size: 14px;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>

@section('scripts')
<script>
$(document).ready(function() {
    // Favorite button handler
    $('.favorite-btn').click(function() {
        const recipeId = $(this).data('recipe-id');
        const button = $(this);
        const heartIcon = button.find('i');
        
        $.ajax({
            url: '{{ route("premium.recipes.favorite", "") }}/' + recipeId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (response.is_favorite) {
                        heartIcon.removeClass('far').addClass('fas');
                        heartIcon.css('color', '#FF6B8B');
                        showToast('Resep ditambahkan ke favorit!', 'success');
                    } else {
                        heartIcon.removeClass('fas').addClass('far');
                        heartIcon.css('color', '');
                        showToast('Resep dihapus dari favorit', 'info');
                    }
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    showToast('Anda perlu upgrade ke premium untuk fitur ini', 'warning');
                } else {
                    showToast('Terjadi kesalahan', 'error');
                }
            }
        });
    });
    
    function showToast(message, type) {
        // Simple alert untuk sekarang
        alert(message);
    }
});
</script>
@endsection
@endsection