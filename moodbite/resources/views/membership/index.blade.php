@extends('layouts.app')

@section('title', 'Membership Premium - MoodBite')

@section('content')
<div class="row mb-5">
    <div class="col-12 text-center">
        <h1 style="color: #FF6B8B;">Upgrade ke Premium</h1>
        <p class="lead">Dapatkan pengalaman terbaik dengan membership premium</p>
        
        @if($user->isPremium())
        <div class="alert alert-success" style="border-left: 4px solid #FF6B8B;">
            <div class="d-flex align-items-center">
                <i class="fas fa-crown fa-2x me-3" style="color: #FFD700;"></i>
                <div>
                    <h5 class="mb-1">Anda sudah Premium!</h5>
                    <p class="mb-0">
                        Membership Anda aktif sampai 
                        <strong>{{ $currentMembership->end_date->format('d F Y') }}</strong>
                        ({{ $currentMembership->type_label }})
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="row">
    @foreach($plans as $type => $plan)
    <div class="col-md-4 mb-4">
        <div class="card h-100 membership-card {{ $plan['best_value'] ? 'popular' : '' }}">
            @if($plan['best_value'])
            <div class="popular-badge">
                <span class="badge">TERPOPULER</span>
            </div>
            @endif
            
            <div class="card-body text-center">
                <h5 class="card-title mb-3">{{ $plan['name'] }}</h5>
                
                <div class="price mb-4">
                    <h2 style="color: #FF6B8B;">Rp {{ number_format($plan['price'], 0, ',', '.') }}</h2>
                    <p class="text-muted">/{{ $plan['period'] }}</p>
                </div>
                
                <ul class="list-unstyled mb-4">
                    @foreach($plan['features'] as $feature)
                    <li class="mb-2">
                        <i class="fas fa-check-circle me-2" style="color: #98FF98;"></i>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                
                @if($user->isPremium() && $user->premium_type == $type)
                <button class="btn btn-success w-100" disabled>
                    <i class="fas fa-check me-2"></i>Aktif
                </button>
                @else
                <form action="{{ route('membership.purchase') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="btn {{ $plan['best_value'] ? 'btn-primary' : 'btn-outline-primary' }} w-100">
                        <i class="fas fa-crown me-2"></i>Pilih Paket
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Benefits Section -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #FFF0F5, #F0F8FF);">
            <div class="card-body">
                <h3 class="text-center mb-4" style="color: #FF6B8B;">Apa yang Anda Dapatkan?</h3>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-star fa-2x me-3" style="color: #FFD166;"></i>
                            </div>
                            <div>
                                <h5>Rekomendasi Premium</h5>
                                <p class="text-muted">Akses ke restoran eksklusif dan menu premium yang tidak tersedia untuk member biasa.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marked-alt fa-2x me-3" style="color: #87CEEB;"></i>
                            </div>
                            <div>
                                <h5>Detail Lengkap</h5>
                                <p class="text-muted">Informasi lengkap termasuk tips menuju lokasi, jam operasional, dan kontak restoran.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-utensils fa-2x me-3" style="color: #98FF98;"></i>
                            </div>
                            <div>
                                <h5>Resep Eksklusif</h5>
                                <p class="text-muted">Akses ke resep-resep khusus dari chef ternama untuk dibuat di rumah.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-percentage fa-2x me-3" style="color: #C8A2C8;"></i>
                            </div>
                            <div>
                                <h5>Diskon Eksklusif</h5>
                                <p class="text-muted">Diskon khusus di berbagai restoran partner kami di seluruh Indonesia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Button -->
<div class="row mt-4">
    <div class="col-12 text-center">
        <a href="{{ route('membership.history') }}" class="btn btn-outline-primary">
            <i class="fas fa-history me-2"></i>Lihat Riwayat Membership
        </a>
    </div>
</div>

<style>
    .membership-card {
        border: 2px solid #FFE6E6;
        border-radius: 15px;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .membership-card.popular {
        border-color: #FF6B8B;
        transform: scale(1.05);
    }
    
    .membership-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(255, 107, 139, 0.2);
    }
    
    .popular-badge {
        position: absolute;
        top: -12px;
        right: 20px;
    }
    
    .popular-badge .badge {
        background: linear-gradient(135deg, #FF6B8B, #FFD166);
        color: white;
        font-weight: 700;
        padding: 5px 15px;
        border-radius: 20px;
    }
    
    .price h2 {
        font-weight: 800;
    }
</style>
@endsection