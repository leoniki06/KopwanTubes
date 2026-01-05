@extends('layouts.app')

@section('title', 'Resep Eksklusif - MoodBite Premium')

@section('content')
@php
    $user = auth()->user();
    $premiumUntil = optional($user?->premium_until)->format('d M Y');
@endphp

<div class="container py-5">

    {{-- HERO --}}
    <div class="recipes-hero mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="hero-pill">
                    <i class="fas fa-crown me-2"></i> PREMIUM RECIPE LIBRARY
                </span>
                <h2 class="fw-black mt-3 mb-1 text-white">🍳 Resep Eksklusif</h2>
                <p class="text-white-50 mb-0">
                    Akses resep premium dari chef ternama & menu mood-based khusus member premium.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap align-items-center">
                <div class="premium-date-card">
                    <div class="small text-white-50">Premium aktif sampai</div>
                    <div class="fw-black text-white">{{ $premiumUntil ?? '-' }}</div>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-hero-outline">
                    <i class="fas fa-user me-2"></i> Profil
                </a>
            </div>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div class="card card-soft border-0 rounded-5 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                <div>
                    <h5 class="fw-black mb-1">
                        <i class="fas fa-filter me-2 text-accent"></i> Filter Resep
                    </h5>
                    <p class="text-muted mb-0 small">Pilih mood, kesulitan, dan waktu masak untuk menemukan resep yang pas.</p>
                </div>

                <div class="filter-badges">
                    <span class="badge-soft">
                        <i class="fas fa-star me-1"></i> Premium Only
                    </span>
                    <span class="badge-soft">
                        <i class="fas fa-utensils me-1"></i> Chef Approved
                    </span>
                </div>
            </div>

            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">Mood</label>
                    <select name="mood" class="form-select form-select-soft">
                        <option value="">Semua Mood</option>
                        <option value="romantis" {{ request('mood') == 'romantis' ? 'selected' : '' }}>Romantis</option>
                        <option value="bahagia" {{ request('mood') == 'bahagia' ? 'selected' : '' }}>Bahagia</option>
                        <option value="sedih" {{ request('mood') == 'sedih' ? 'selected' : '' }}>Sedih</option>
                        <option value="semangat" {{ request('mood') == 'semangat' ? 'selected' : '' }}>Semangat</option>
                        <option value="stres" {{ request('mood') == 'stres' ? 'selected' : '' }}>Stres</option>

                        <option value="anxious" {{ request('mood') == 'anxious' ? 'selected' : '' }}>Anxious</option>
                        <option value="lazy" {{ request('mood') == 'lazy' ? 'selected' : '' }}>Lazy</option>
                        <option value="rainy" {{ request('mood') == 'rainy' ? 'selected' : '' }}>Rainy</option>
                        <option value="focus" {{ request('mood') == 'focus' ? 'selected' : '' }}>Focus</option>
                        <option value="sleepy" {{ request('mood') == 'sleepy' ? 'selected' : '' }}>Sleepy</option>
                        <option value="party" {{ request('mood') == 'party' ? 'selected' : '' }}>Party</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">Kesulitan</label>
                    <select name="difficulty" class="form-select form-select-soft">
                        <option value="">Semua Level</option>
                        <option value="Mudah" {{ request('difficulty') == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="Sedang" {{ request('difficulty') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Sulit" {{ request('difficulty') == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">Waktu Masak</label>
                    <select name="time" class="form-select form-select-soft">
                        <option value="">Semua Waktu</option>
                        <option value="fast" {{ request('time') == 'fast' ? 'selected' : '' }}>Cepat (≤ 30 menit)</option>
                        <option value="medium" {{ request('time') == 'medium' ? 'selected' : '' }}>Sedang (31–60 menit)</option>
                        <option value="slow" {{ request('time') == 'slow' ? 'selected' : '' }}>Lama (> 60 menit)</option>
                    </select>
                </div>

                <div class="col-12 text-center mt-2">
                    <button type="submit" class="btn btn-accent me-2">
                        <i class="fas fa-filter me-2"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('premium.recipes.index') }}" class="btn btn-outline-accent">
                        <i class="fas fa-rotate-left me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- GRID --}}
    @if($recipes->count() > 0)
        <div class="row g-4">
            @foreach($recipes as $recipe)
                <div class="col-md-4">
                    <div class="recipe-card h-100">

                        <div class="recipe-top">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <span class="pill-premium">
                                    <i class="fas fa-star me-1"></i> PREMIUM
                                </span>

                                <span class="pill-chef">
                                    <i class="fas fa-user-tie me-1"></i> {{ $recipe->chef_name }}
                                </span>
                            </div>

                            <h6 class="fw-black recipe-title mt-3 mb-2">
                                {{ $recipe->recipe_name }}
                            </h6>

                            <p class="text-muted small recipe-desc mb-3">
                                {{ \Illuminate\Support\Str::limit($recipe->description, 95) }}
                            </p>

                            <div class="recipe-meta">
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $recipe->cooking_time }}m</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ $recipe->servings }} porsi</span>
                                </div>
                                <span class="pill-difficulty">
                                    {{ $recipe->difficulty }}
                                </span>
                            </div>
                        </div>

                        <div class="recipe-footer">
                            @if($recipe->mood_category)
                                <span class="pill-mood">
                                    <i class="fas fa-face-smile me-1"></i> {{ ucfirst($recipe->mood_category) }}
                                </span>
                            @else
                                <span></span>
                            @endif

                            <div class="d-flex gap-2">
                                <button class="btn btn-fav favorite-btn"
                                        data-recipe-id="{{ $recipe->id }}">
                                    <i class="far fa-heart"></i>
                                </button>

                                <a href="{{ route('premium.recipes.show', $recipe->id) }}"
                                   class="btn btn-accent btn-sm px-3">
                                    <i class="fas fa-book-open me-2"></i> Lihat
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $recipes->links() }}
        </div>

    @else
        <div class="empty-wrap text-center py-5">
            <div class="empty-icon">
                <i class="fas fa-utensils"></i>
            </div>
            <h5 class="fw-black mt-3">Tidak ada resep ditemukan</h5>
            <p class="text-muted mb-4">Coba gunakan filter yang berbeda ya ✨</p>
            <a href="{{ route('premium.recipes.index') }}" class="btn btn-accent px-4">
                <i class="fas fa-rotate-left me-2"></i> Reset Filter
            </a>
        </div>
    @endif
</div>

<style>
:root{
    --accent:#FF6B8B;
    --accent-2:#FFB6C8;
    --soft:#FFF6F8;
    --text:#111827;
    --muted:#6b7280;
}

.fw-black{ font-weight: 900; }
.text-accent{ color: var(--accent) !important; }

body{
    background: #F9FAFB !important;
}

/* HERO */
.recipes-hero{
    border-radius: 26px;
    padding: 24px;
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    box-shadow: 0 18px 35px rgba(255,107,139,.16);
}

.hero-pill{
    display:inline-flex;
    align-items:center;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 12px;
    color: #fff;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.25);
}

.btn-hero-outline{
    background: rgba(255,255,255,.12);
    border: 2px solid rgba(255,255,255,.45);
    padding: 10px 16px;
    border-radius: 14px;
    font-weight: 900;
    color:#fff;
}
.btn-hero-outline:hover{
    background: rgba(255,255,255,.22);
    color:#fff;
}

.premium-date-card{
    padding: 10px 14px;
    border-radius: 16px;
    background: rgba(255,255,255,.16);
    border: 1px solid rgba(255,255,255,.25);
}

/* Card Soft */
.card-soft{
    background: #fff;
    border: 1px solid rgba(0,0,0,.05);
}

/* Filter badge */
.filter-badges{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}
.badge-soft{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size: 12px;
    font-weight: 900;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(255,107,139,.10);
    border: 1px solid rgba(255,107,139,.14);
    color: var(--accent);
}

/* Form */
.form-select-soft{
    border-radius: 14px;
    padding: 12px 14px;
    border: 1px solid rgba(0,0,0,.08);
    background: #fff;
    font-weight: 600;
}
.form-select-soft:focus{
    box-shadow: 0 0 0 .25rem rgba(255,107,139,.14);
    border-color: rgba(255,107,139,.4);
}

/* Buttons */
.btn-accent{
    background: var(--accent);
    border:none;
    color:#fff;
    border-radius: 14px;
    font-weight: 900;
    padding: 10px 18px;
    transition:.2s ease;
}
.btn-accent:hover{
    background: #E05575;
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(0,0,0,.10);
    color:#fff;
}
.btn-outline-accent{
    background: transparent;
    border: 2px solid var(--accent);
    color: var(--accent);
    border-radius: 14px;
    font-weight: 900;
    padding: 10px 18px;
    transition:.2s ease;
}
.btn-outline-accent:hover{
    background: var(--accent);
    color:#fff;
}

/* Recipe Card */
.recipe-card{
    border-radius: 22px;
    background: #fff;
    border: 1px solid rgba(0,0,0,.06);
    box-shadow: 0 10px 25px rgba(0,0,0,.06);
    overflow: hidden;
    display:flex;
    flex-direction:column;
    height:100%;
    transition:.2s ease;
}
.recipe-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 18px 45px rgba(0,0,0,.12);
}

.recipe-top{
    padding: 18px 18px 14px;
    flex: 1 1 auto;
}

.recipe-footer{
    padding: 14px 18px 18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-top: 1px solid rgba(0,0,0,.05);
    background: rgba(255,107,139,.03);
}

/* Pills */
.pill-premium{
    background: rgba(255,107,139,.10);
    border: 1px solid rgba(255,107,139,.14);
    color: var(--accent);
    font-weight: 900;
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 12px;
}
.pill-chef{
    max-width: 60%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    background: rgba(17,24,39,.06);
    border: 1px solid rgba(17,24,39,.08);
    color: #374151;
    font-weight: 800;
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 12px;
}
.pill-difficulty{
    background: rgba(17,24,39,.06);
    border: 1px solid rgba(17,24,39,.08);
    color: #111827;
    font-weight: 900;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
}
.pill-mood{
    max-width: 50%;
    white-space: nowrap;
    overflow:hidden;
    text-overflow: ellipsis;
    background: rgba(255,107,139,.12);
    border: 1px solid rgba(255,107,139,.16);
    color: var(--accent);
    font-weight: 900;
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 12px;
}

/* Text clamp */
.recipe-title{
    min-height: 44px;
    display:-webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow:hidden;
}
.recipe-desc{
    min-height: 46px;
    display:-webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow:hidden;
}

.recipe-meta{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}
.meta-item{
    display:flex;
    align-items:center;
    gap:8px;
    color: var(--muted);
    font-weight: 700;
    font-size: 13px;
}
.meta-item i{
    color: var(--accent);
}

/* Favorite button */
.btn-fav{
    border-radius: 14px;
    border: 2px solid rgba(255,107,139,.25);
    background: #fff;
    padding: 8px 12px;
    font-weight: 900;
    transition:.2s ease;
}
.btn-fav:hover{
    background: rgba(255,107,139,.08);
    transform: translateY(-1px);
}
.btn-fav i{
    color: var(--accent);
}

/* Empty */
.empty-wrap{
    border-radius: 22px;
    background: rgba(255,107,139,.04);
    border: 1px dashed rgba(255,107,139,.22);
    padding: 40px 20px;
}
.empty-icon{
    width: 70px;
    height: 70px;
    border-radius: 22px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin: 0 auto;
    background: rgba(255,107,139,.12);
    color: var(--accent);
    font-size: 26px;
}
</style>

@section('scripts')
<script>
$(document).ready(function() {
    $('.favorite-btn').click(function() {
        const recipeId = $(this).data('recipe-id');
        const button = $(this);
        const heartIcon = button.find('i');

        $.ajax({
            url: '{{ url("/premium/recipes") }}/' + recipeId + '/favorite',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (response.is_favorite) {
                        heartIcon.removeClass('far').addClass('fas');
                        heartIcon.css('color', '#FF6B8B');
                    } else {
                        heartIcon.removeClass('fas').addClass('far');
                        heartIcon.css('color', '#FF6B8B');
                    }
                }
            },
            error: function() {
                alert('Terjadi kesalahan');
            }
        });
    });
});
</script>
@endsection
@endsection
