@extends('layouts.app')

@section('title', $recipe->recipe_name . ' - Resep Premium')

@section('content')
<div class="container py-5">

    <!-- Top actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('premium.recipes.index') }}" class="btn btn-outline-pink">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>

        <button class="btn btn-outline-pink favorite-btn" data-recipe-id="{{ $recipe->id }}">
            <i class="far fa-heart"></i>
        </button>
    </div>

    <!-- Header card -->
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body p-4">
            <span class="badge bg-pink-soft text-pink mb-2">
                <i class="fas fa-star me-1"></i> PREMIUM
            </span>

            <h1 class="fw-bold mb-1">{{ $recipe->recipe_name }}</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user-tie me-1"></i> {{ $recipe->chef_name }}
            </p>

            <div class="d-flex flex-wrap gap-3 text-muted mt-3 align-items-center">
                <span><i class="fas fa-clock me-1"></i>{{ $recipe->cooking_time }} menit</span>
                <span><i class="fas fa-users me-1"></i>{{ $recipe->servings }} porsi</span>

                <span class="badge bg-light text-dark">{{ $recipe->difficulty }}</span>

                @if($recipe->mood_category)
                    <span class="badge bg-pink-light text-pink">
                        <i class="fas fa-smile me-1"></i>{{ ucfirst($recipe->mood_category) }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-pink mb-3">
                <i class="fas fa-align-left me-2"></i>Deskripsi
            </h5>

            @php
                $desc = $recipe->description ?? '';
                if (is_array($desc)) $desc = implode(' ', $desc);
            @endphp

            <p class="mb-0">{{ $desc }}</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Ingredients -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-pink mb-3">
                        <i class="fas fa-carrot me-2"></i>Bahan-bahan
                    </h5>

                    @php
                        $ingredientsRaw = $recipe->ingredients ?? '[]';

                        // jika ingredients berupa string JSON
                        if (is_string($ingredientsRaw)) {
                            $decoded = json_decode($ingredientsRaw, true);
                            $ingredients = is_array($decoded) ? $decoded : [];
                        } elseif (is_array($ingredientsRaw)) {
                            $ingredients = $ingredientsRaw;
                        } else {
                            $ingredients = [];
                        }
                    @endphp

                    @if(count($ingredients) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($ingredients as $ingredient)
                                @php
                                    $name = is_array($ingredient) ? ($ingredient['name'] ?? '') : (string) $ingredient;

                                    // support key seeder kamu: quantity + unit
                                    // juga support kemungkinan data lama: qty + unit
                                    $qty  = is_array($ingredient) ? ($ingredient['quantity'] ?? ($ingredient['qty'] ?? '')) : '';
                                    $unit = is_array($ingredient) ? ($ingredient['unit'] ?? '') : '';

                                    $qtyText = trim((string)$qty);
                                    $unitText = trim((string)$unit);

                                    // gabung qty + unit kalau qty ada
                                    $finalQty = trim($qtyText . ' ' . $unitText);
                                @endphp

                                <li class="list-group-item px-0 d-flex align-items-start justify-content-between gap-3">
                                    <div class="d-flex align-items-start gap-2">
                                        <span class="dot mt-2"></span>
                                        <div class="fw-semibold">{{ $name ?: 'Bahan' }}</div>
                                    </div>

                                    @if(!empty($qtyText))
                                        <span class="badge bg-pink-soft text-pink qty-badge">
                                            {{ $finalQty }}
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Bahan belum tersedia.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Steps -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-pink mb-3">
                        <i class="fas fa-list-ol me-2"></i>Langkah Memasak
                    </h5>

                    @php
                        $stepsRaw = $recipe->step_by_step ?? '[]';

                        // jika step_by_step berupa string JSON
                        if (is_string($stepsRaw)) {
                            $decoded = json_decode($stepsRaw, true);
                            $steps = is_array($decoded) ? $decoded : [];
                        } elseif (is_array($stepsRaw)) {
                            $steps = $stepsRaw;
                        } else {
                            $steps = [];
                        }

                        // urutkan step berdasarkan step_number jika ada
                        if (is_array($steps)) {
                            usort($steps, function($a, $b) {
                                $sa = is_array($a) ? ($a['step_number'] ?? 0) : 0;
                                $sb = is_array($b) ? ($b['step_number'] ?? 0) : 0;
                                return $sa <=> $sb;
                            });
                        } else {
                            $steps = [];
                        }
                    @endphp

                    @if(count($steps) > 0)
                        <ol class="list-group list-group-numbered list-group-flush">
                            @foreach($steps as $step)
                                @php
                                    $instruction = is_array($step) ? ($step['instruction'] ?? '') : (string) $step;
                                    $duration = is_array($step) ? ($step['duration'] ?? '') : '';
                                    $tip = is_array($step) ? ($step['tip'] ?? '') : '';
                                @endphp

                                <li class="list-group-item px-0">
                                    <div class="fw-semibold">{{ $instruction }}</div>

                                    @if(!empty($duration) || !empty($tip))
                                        <div class="mt-2 small text-muted d-flex flex-wrap gap-2">
                                            @if(!empty($duration))
                                                <span class="badge bg-light text-dark">⏱ {{ $duration }}</span>
                                            @endif
                                            @if(!empty($tip))
                                                <span class="badge bg-pink-soft text-pink">💡 {{ $tip }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="text-muted mb-0">Langkah memasak belum tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Video -->
    @if(!empty($recipe->video_url))
        <div class="card border-0 shadow-sm mt-4 rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold text-pink mb-3">
                    <i class="fas fa-play-circle me-2"></i>Video Tutorial
                </h5>

                @php
                    $url = $recipe->video_url;
                    $videoId = null;

                    // youtube.com/watch?v=xxxx
                    if (preg_match('~v=([^&]+)~', $url, $m)) $videoId = $m[1];
                    // youtu.be/xxxx
                    if (!$videoId && preg_match('~youtu\.be/([^?]+)~', $url, $m)) $videoId = $m[1];
                @endphp

                @if($videoId)
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $videoId }}"
                            title="YouTube video"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                @else
                    <a href="{{ $recipe->video_url }}" target="_blank" class="btn btn-outline-pink">
                        Buka Video
                    </a>
                @endif
            </div>
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

.btn-outline-pink {
    color: var(--pink);
    border: 2px solid var(--pink);
    background: transparent;
    border-radius: 10px;
}
.btn-outline-pink:hover {
    background: var(--pink);
    color: #fff;
}

.list-group-item { border: 0; background: transparent; }

.dot {
    width: 10px;
    height: 10px;
    border-radius: 999px;
    background: var(--pink);
    display: inline-block;
}

.qty-badge {
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 600;
    white-space: nowrap;
}
</style>

@section('scripts')
<script>
$(document).ready(function() {
    $('.favorite-btn').click(function() {
        const recipeId = $(this).data('recipe-id');
        const heartIcon = $(this).find('i');

        $.ajax({
            url: '{{ url("/premium/recipes") }}/' + recipeId + '/favorite',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    if (response.is_favorite) {
                        heartIcon.removeClass('far').addClass('fas').css('color', '#FF6B8B');
                    } else {
                        heartIcon.removeClass('fas').addClass('far').css('color', '');
                    }
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat update favorit.');
            }
        });
    });
});
</script>
@endsection
@endsection