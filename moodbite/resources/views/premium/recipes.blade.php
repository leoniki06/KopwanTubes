@extends('layouts.app')

@section('title', 'Resep Eksklusif - MoodBite Premium')

@section('content')
<div class="container py-4">
    <!-- Premium Header -->
    <div class="text-center mb-5">
        <div class="premium-badge mb-3">
            <i class="fas fa-crown"></i> PREMIUM MEMBER
        </div>
        <h1 class="fw-bold text-dark mb-3">🍳 Resep Eksklusif</h1>
        <p class="text-muted">
            Selamat datang di dunia kuliner premium! Akses resep khusus dari chef ternama.
        </p>

        <!-- Premium Stats -->
        <div class="row justify-content-center mt-4">
            <div class="col-auto">
                <div class="card premium-card">
                    <div class="card-body text-center py-2">
                        <small class="text-muted">Premium Aktif</small>
                        <h5 class="mb-0">{{ optional(auth()->user()->premium_until)->format('d M Y') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-4 border-0 bg-pink-soft">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-pink">Mood</label>
                    <select name="mood" class="form-select">
                        <option value="">Semua Mood</option>
                        <option value="romantis" {{ request('mood') == 'romantis' ? 'selected' : '' }}>Romantis</option>
                        <option value="bahagia" {{ request('mood') == 'bahagia' ? 'selected' : '' }}>Bahagia</option>
                        <option value="sedih" {{ request('mood') == 'sedih' ? 'selected' : '' }}>Sedih</option>
                        <option value="semangat" {{ request('mood') == 'semangat' ? 'selected' : '' }}>Semangat</option>
                        <option value="stres" {{ request('mood') == 'stres' ? 'selected' : '' }}>Stres</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-pink">Kesulitan</label>
                    <select name="difficulty" class="form-select">
                        <option value="">Semua Level</option>
                        <option value="Mudah" {{ request('difficulty') == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="Sedang" {{ request('difficulty') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Sulit" {{ request('difficulty') == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-pink">Waktu Masak</label>
                    <select name="time" class="form-select">
                        <option value="">Semua Waktu</option>
                        <option value="fast" {{ request('time') == 'fast' ? 'selected' : '' }}>Cepat (≤ 30 menit)</option>
                        <option value="medium" {{ request('time') == 'medium' ? 'selected' : '' }}>Sedang (31-60 menit)</option>
                        <option value="slow" {{ request('time') == 'slow' ? 'selected' : '' }}>Lama (> 60 menit)</option>
                    </select>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-pink me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('premium.recipes.index') }}" class="btn btn-outline-pink">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Recipes Grid (RAPI) -->
    @if($recipes->count() > 0)
        <div class="row g-3">
            @foreach($recipes as $recipe)
                <div class="col-md-4">
                    <div class="card premium-recipe-card h-100 border-0 shadow-sm-hover">
                        <div class="card-body premium-card-body">
                            <!-- Badge & Chef -->
                            <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                                <span class="badge bg-pink-soft text-pink">
                                    <i class="fas fa-star me-1"></i> PREMIUM
                                </span>

                                <small class="text-muted text-end premium-chef">
                                    <i class="fas fa-user-tie me-1"></i> {{ $recipe->chef_name }}
                                </small>
                            </div>

                            <!-- Recipe Name -->
                            <h6 class="fw-bold mb-2 premium-title">
                                {{ $recipe->recipe_name }}
                            </h6>

                            <!-- Description (dipotong rapi) -->
                            <p class="text-muted small mb-3 premium-desc">
                                {{ \Illuminate\Support\Str::limit($recipe->description, 90) }}
                            </p>

                            <!-- Stats -->
                            <div class="d-flex justify-content-between align-items-center mb-3 premium-stats">
                                <div class="text-muted small d-flex gap-3 flex-wrap">
                                    <span>
                                        <i class="fas fa-clock me-1"></i> {{ $recipe->cooking_time }}m
                                    </span>
                                    <span>
                                        <i class="fas fa-users me-1"></i> {{ $recipe->servings }} porsi
                                    </span>
                                </div>

                                <span class="badge bg-light text-dark">
                                    {{ $recipe->difficulty }}
                                </span>
                            </div>
                        </div>

                        <!-- Footer selalu nempel bawah -->
                        <div class="premium-card-footer d-flex justify-content-between align-items-center gap-2">
                            @if($recipe->mood_category)
                                <span class="badge bg-pink-light text-pink premium-mood">
                                    <i class="fas fa-smile me-1"></i> {{ ucfirst($recipe->mood_category) }}
                                </span>
                            @else
                                <span></span>
                            @endif

                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-outline-pink btn-sm favorite-btn"
                                        data-recipe-id="{{ $recipe->id }}">
                                    <i class="far fa-heart"></i>
                                </button>

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

        <!-- Pagination -->
        <div class="mt-4">
            {{ $recipes->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Tidak ada resep ditemukan</h4>
            <p class="text-muted">Coba gunakan filter yang berbeda</p>
        </div>
    @endif
</div>

<style>
:root {
    --pink: #FF6B8B;
    --pink-light: #FFACC7;
    --pink-soft: #FFF0F5;
}

.text-pink { color: var(--pink) !important; }
.bg-pink-soft { background-color: var(--pink-soft) !important; }
.bg-pink-light { background-color: var(--pink-light) !important; }

/* Buttons */
.btn-pink {
    background-color: var(--pink);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
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
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.2s;
}
.btn-outline-pink:hover {
    background-color: var(--pink);
    color: white;
}

/* Card hover */
.shadow-sm-hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.shadow-sm-hover:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

/* Badge pink */
.badge.bg-pink-soft {
    background-color: var(--pink-soft) !important;
    color: var(--pink) !important;
    font-weight: 600;
    padding: 5px 12px;
}
.badge.bg-pink-light {
    background-color: var(--pink-light) !important;
    color: var(--pink) !important;
    font-weight: 600;
    padding: 5px 12px;
}

/* Header badge premium */
.premium-badge {
    display: inline-block;
    background: linear-gradient(135deg, #FF6B8B, #C8A2C8);
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: bold;
}

.premium-card {
    border: 2px solid var(--pink);
    border-radius: 12px;
}

/* ========= BIAR RAPI ========= */
.premium-recipe-card{
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    flex-direction: column;  /* bikin footer nempel bawah */
}

.premium-card-body{
    flex: 1 1 auto;
    padding: 18px;
}

.premium-card-footer{
    margin-top: auto;
    padding: 14px 18px 18px;
}

/* judul max 2 baris */
.premium-title{
    min-height: 40px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* deskripsi max 2 baris */
.premium-desc{
    min-height: 44px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* chef & mood jangan bikin layout amburadul */
.premium-chef, .premium-mood{
    max-width: 60%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
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
                        heartIcon.css('color', '');
                    }
                    alert(response.message || 'Berhasil');
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
