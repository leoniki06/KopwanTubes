@extends('layouts.app')

@section('title', 'Profil Saya - MoodBite')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <!-- Avatar -->
                <div class="mb-3">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" 
                             class="rounded-circle" width="150" height="150" alt="Avatar">
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px; background: linear-gradient(135deg, #FF6B8B, #FFD166);">
                            <span class="text-white display-4">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                
                <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                <p class="text-muted mb-2">{{ Auth::user()->email }}</p>

                {{-- STATUS PREMIUM --}}
                @if(Auth::user()->isPremium())
                    <span class="badge mb-2"
                          style="background: linear-gradient(135deg, #FFD166, #FF6B8B); color: #333;">
                        <i class="fas fa-crown me-1"></i> Premium Member
                    </span>
                    <p class="small text-muted">
                        Aktif sampai {{ Auth::user()->premium_until->format('d F Y') }}
                    </p>
                @else
                    <span class="badge bg-secondary mb-3">
                        Free Member
                    </span>
                @endif

                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Food Preferences Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-heart me-2" style="color: #FF6B8B;"></i>
                    Preferensi Makanan
                </h5>
                
                <form action="{{ route('profile.preferences') }}" method="POST">
                    @csrf
                    
                    @php
                        $preferences = Auth::user()->food_preferences ?? [];
                    @endphp
                    
                    @foreach([
                        'vegetarian' => 'Vegetarian',
                        'vegan' => 'Vegan',
                        'halal' => 'Halal',
                        'spicy' => 'Suka Pedas',
                        'sweet' => 'Suka Manis',
                        'low_calorie' => 'Rendah Kalori'
                    ] as $key => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="preferences[]"
                                   value="{{ $key }}"
                                   id="pref_{{ $key }}"
                                   {{ in_array($key, $preferences) ? 'checked' : '' }}>
                            <label class="form-check-label" for="pref_{{ $key }}">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                    
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100 mt-3">
                        <i class="fas fa-save me-1"></i>Simpan Preferensi
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Profile Details Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-user-circle me-2" style="color: #FF6B8B;"></i>
                    Informasi Profil
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Nama Lengkap</h6>
                        <p class="mb-0">{{ Auth::user()->name }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Email</h6>
                        <p class="mb-0">{{ Auth::user()->email }}</p>
                    </div>
                    
                    @if(Auth::user()->phone)
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Telepon</h6>
                        <p class="mb-0">{{ Auth::user()->formatted_phone }}</p>
                    </div>
                    @endif
                    
                    @if(Auth::user()->birthdate)
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Tanggal Lahir</h6>
                        <p class="mb-0">
                            {{ Auth::user()->birthdate->format('d F Y') }}
                        </p>
                    </div>
                    @endif
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Member Sejak</h6>
                        <p class="mb-0">{{ Auth::user()->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Recommendations -->
        <div class="card mt-4">
            <div class="card-body text-center py-4">
                <i class="fas fa-utensils fa-3x mb-3" style="color: #FF6B8B; opacity: 0.5;"></i>
                <p class="text-muted">Belum ada riwayat rekomendasi</p>
                <a href="{{ route('recommendations') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Cari Rekomendasi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
