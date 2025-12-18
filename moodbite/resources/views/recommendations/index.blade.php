@extends('layouts.app')

@section('title', 'Cari Makanan - MoodBite')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 style="color: #555;">Cari Makanan Sesuai Mood</h2>
        <p class="lead" style="color: #777;">Pilih suasana hatimu untuk mendapatkan rekomendasi makanan yang tepat</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('recommendations') }}" method="POST">
                    @csrf
                    
                    <h5 class="mb-4" style="color: #555;">Bagaimana perasaanmu hari ini?</h5>
                    
                    <div class="row">
                        @php
                            $moodOptions = [
                                'happy' => ['icon' => 'fa-smile', 'label' => 'Bahagia', 'color' => '#FFD166'],
                                'sad' => ['icon' => 'fa-sad-tear', 'label' => 'Sedih', 'color' => '#87CEEB'],
                                'energetic' => ['icon' => 'fa-bolt', 'label' => 'Berenergi', 'color' => '#98FF98'],
                                'stress' => ['icon' => 'fa-wind', 'label' => 'Stress', 'color' => '#C8A2C8'],
                                'romantic' => ['icon' => 'fa-heart', 'label' => 'Romantis', 'color' => '#FF9AA2'],
                                'hungry' => ['icon' => 'fa-hamburger', 'label' => 'Lapar', 'color' => '#FFB347'],
                            ];
                        @endphp
                        
                        @foreach($moodOptions as $key => $option)
                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                            <input type="radio" class="btn-check" name="mood" id="mood-{{ $key }}" 
                                   value="{{ $key }}" required>
                            <label class="btn btn-outline-primary w-100 py-3" for="mood-{{ $key }}"
                                   style="border-color: {{ $option['color'] }}; color: {{ $option['color'] }};">
                                <i class="fas {{ $option['icon'] }} fa-2x mb-2 d-block"></i>
                                {{ $option['label'] }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    
                    @error('mood')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-search me-2"></i>Cari Rekomendasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($moods) && count($moods) > 0)
<div class="row mt-5">
    <div class="col-12">
        <h4>Mood Tersedia</h4>
        <div class="row">
            @foreach($moods as $moodItem)
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('recommendations') }}?mood={{ $moodItem->mood }}" 
                   class="text-decoration-none">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            @php
                                $icon = match($moodItem->mood) {
                                    'happy' => 'fa-smile',
                                    'sad' => 'fa-sad-tear',
                                    'energetic' => 'fa-bolt',
                                    'stress' => 'fa-wind',
                                    'romantic' => 'fa-heart',
                                    default => 'fa-question'
                                };
                            @endphp
                            <i class="fas {{ $icon }} fa-3x mb-3" style="color: #FF6B8B;"></i>
                            <h5 class="card-title">{{ ucfirst($moodItem->mood) }}</h5>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection