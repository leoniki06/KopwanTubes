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
                        <h5 class="mb-0">{{ auth()->user()->premium_until->format('d M Y') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-4 border-warning">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Mood</label>
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
                    <label class="form-label">Kesulitan</label>
                    <select name="difficulty" class="form-select">
                        <option value="">Semua Level</option>
                        <option value="Mudah" {{ request('difficulty') == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                        <option value="Sedang" {{ request('difficulty') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Sulit" {{ request('difficulty') == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Waktu Masak</label>
                    <select name="time" class="form-select">
                        <option value="">Semua Waktu</option>
                        <option value="fast" {{ request('time') == 'fast' ? 'selected' : '' }}>Cepat (≤ 30 menit)</option>
                        <option value="medium" {{ request('time') == 'medium' ? 'selected' : '' }}>Sedang (31-60 menit)</option>
                        <option value="slow" {{ request('time') == 'slow' ? 'selected' : '' }}>Lama (> 60 menit)</option>
                    </select>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-warning me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('premium.recipes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Recipes Grid -->
    @if($recipes->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($recipes as $recipe)
                <div class="col">
                    <div class="card h-100 premium-recipe-card">
                        <!-- Premium Badge -->
                        <div class="premium-ribbon">EXCLUSIVE</div>
                        
                        <!-- Recipe Image -->
                        <div class="recipe-image" style="background: linear-gradient(45deg, #f59e0b, #fbbf24); height: 180px;">
                            <div class="h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-utensils text-white fa-3x"></i>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Chef Info -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="chef-avatar bg-warning rounded-circle p-2 me-3">
                                    <i class="fas fa-chef-hat text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $recipe->chef_name }}</h6>
                                    <small class="text-muted">Master Chef</small>
                                </div>
                            </div>
                            
                            <!-- Recipe Name -->
                            <h5 class="card-title fw-bold">{{ $recipe->recipe_name }}</h5>
                            
                            <!-- Description -->
                            <p class="card-text text-muted small">
                                {{ Str::limit($recipe->description, 100) }}
                            </p>
                            
                            <!-- Recipe Stats -->
                            <div class="recipe-stats mb-3">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-clock text-warning"></i> {{ $recipe->cooking_time }}m
                                </span>
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-users text-warning"></i> {{ $recipe->servings }} porsi
                                </span>
                                <span class="badge bg-{{ $recipe->difficulty == 'Mudah' ? 'success' : ($recipe->difficulty == 'Sedang' ? 'warning' : 'danger') }}">
                                    {{ $recipe->difficulty }}
                                </span>
                            </div>
                            
                            <!-- Mood Badge -->
                            @if($recipe->mood_category)
                                <div class="mb-3">
                                    <span class="badge bg-info">
                                        <i class="fas fa-smile"></i> {{ ucfirst($recipe->mood_category) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('premium.recipes.show', $recipe->id) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-book-open me-1"></i> Lihat Resep
                                </a>
                                <button class="btn btn-outline-warning btn-sm favorite-btn" 
                                        data-recipe-id="{{ $recipe->id }}">
                                    <i class="far fa-heart"></i>
                                </button>
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
.premium-badge {
    display: inline-block;
    background: linear-gradient(45deg, #f59e0b, #fbbf24);
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: bold;
}

.premium-card {
    border: 2px solid #f59e0b;
    border-radius: 10px;
}

.premium-recipe-card {
    border: 2px solid #fef3c7;
    transition: transform 0.3s;
}

.premium-recipe-card:hover {
    transform: translateY(-5px);
    border-color: #f59e0b;
}

.premium-ribbon {
    position: absolute;
    top: 10px;
    right: -10px;
    background: #f59e0b;
    color: white;
    padding: 5px 15px;
    font-size: 12px;
    font-weight: bold;
    transform: rotate(45deg);
    z-index: 1;
}

.chef-avatar {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.recipe-image {
    border-radius: 8px 8px 0 0;
    overflow: hidden;
}

.recipe-stats .badge {
    font-size: 12px;
    padding: 5px 10px;
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
                        heartIcon.removeClass('far').addClass('fas text-danger');
                    } else {
                        heartIcon.removeClass('fas text-danger').addClass('far');
                    }
                    
                    // Show toast notification
                    showToast(response.message, 'success');
                }
            },
            error: function(xhr) {
                showToast('Terjadi kesalahan', 'error');
            }
        });
    });
    
    function showToast(message, type) {
        // Implement toast notification here
        alert(message); // Ganti dengan library toast Anda
    }
});
</script>
@endsection
@endsection