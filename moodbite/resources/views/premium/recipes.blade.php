@extends('layouts.app')

@section('title', 'Resep Eksklusif - MoodBite Premium')

@section('content')
<div class="container py-5">
    <!-- Premium Header -->
    <div class="text-center mb-5">
        <div class="d-inline-block px-4 py-2 bg-gradient-primary rounded-pill mb-3">
            <i class="fas fa-crown me-2"></i>PREMIUM EXCLUSIVE
        </div>
        <h1 class="display-5 fw-bold text-dark mb-3">
            Resep Eksklusif dari Chef Ternama
        </h1>
        <p class="lead text-muted">
            Akses ke resep-resep khusus dari chef ternama untuk dibuat di rumah
        </p>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-chef-hat text-warning me-2"></i>
                        {{ $recipes->total() }} Resep Eksklusif Tersedia
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <form method="GET" class="d-inline">
                        <select name="mood" class="form-select w-auto d-inline" onchange="this.form.submit()">
                            <option value="">Semua Mood</option>
                            <option value="bahagia" {{ request('mood') == 'bahagia' ? 'selected' : '' }}>Bahagia</option>
                            <option value="sedih" {{ request('mood') == 'sedih' ? 'selected' : '' }}>Sedih</option>
                            <option value="stres" {{ request('mood') == 'stres' ? 'selected' : '' }}>Stres</option>
                            <option value="romantis" {{ request('mood') == 'romantis' ? 'selected' : '' }}>Romantis</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recipes Grid -->
    @if($recipes->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($recipes as $recipe)
                <div class="col">
                    <div class="card h-100 border-warning shadow-sm hover-shadow">
                        <!-- Premium Badge -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>PREMIUM
                            </span>
                        </div>

                        <!-- Recipe Image -->
                        <img src="{{ $recipe->chef_photo ?: asset('images/default-chef.jpg') }}" 
                             class="card-img-top" 
                             alt="{{ $recipe->chef_name }}"
                             style="height: 200px; object-fit: cover;">

                        <div class="card-body">
                            <!-- Chef Info -->
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-warning p-2 rounded-circle me-3">
                                    <i class="fas fa-utensils text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $recipe->chef_name }}</h6>
                                    <small class="text-muted">Executive Chef</small>
                                </div>
                            </div>

                            <!-- Recipe Name -->
                            <h5 class="card-title fw-bold">{{ $recipe->recipe_name }}</h5>
                            
                            <!-- Description -->
                            <p class="card-text text-muted">
                                {{ Str::limit($recipe->description, 100) }}
                            </p>

                            <!-- Recipe Stats -->
                            <div class="d-flex justify-content-between text-muted small mb-3">
                                <span>
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $recipe->cooking_time }} menit
                                </span>
                                <span>
                                    <i class="fas fa-user-friends me-1"></i>
                                    {{ $recipe->servings }} porsi
                                </span>
                                <span class="badge bg-{{ 
                                    $recipe->difficulty == 'Mudah' ? 'success' : 
                                    ($recipe->difficulty == 'Sedang' ? 'warning' : 'danger') 
                                }}">
                                    {{ $recipe->difficulty }}
                                </span>
                            </div>

                            <!-- Mood Badge -->
                            @if($recipe->mood_category)
                                <div class="mb-3">
                                    <span class="badge bg-info">
                                        <i class="fas fa-smile me-1"></i>
                                        {{ ucfirst($recipe->mood_category) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('premium.recipes.show', $recipe->id) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-book-open me-1"></i>Lihat Resep
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
        <div class="mt-5">
            {{ $recipes->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Belum ada resep tersedia</h4>
            <p class="text-muted">Resep eksklusif sedang dalam persiapan oleh chef kami</p>
        </div>
    @endif
</div>

<!-- JavaScript untuk Favorit -->
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function() {
            const recipeId = this.dataset.recipeId;
            fetch(`/premium/recipes/${recipeId}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const icon = this.querySelector('i');
                    icon.className = 'fas fa-heart text-danger';
                    showToast('Resep ditambahkan ke favorit!', 'success');
                }
            });
        });
    });

    function showToast(message, type) {
        // Implement toast notification
        alert(message); // Ganti dengan library toast Anda
    }
});
</script>
@endsection
@endsection