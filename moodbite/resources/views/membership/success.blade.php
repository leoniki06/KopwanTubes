@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - MoodBite')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card text-center">
            <div class="card-body py-5">
                <!-- Success Icon -->
                <div class="success-icon mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; background: linear-gradient(135deg, #98FF98, #B5EAD7);">
                        <i class="fas fa-check fa-3x text-white"></i>
                    </div>
                </div>
                
                <!-- Success Message -->
                <h2 style="color: #FF6B8B;">Pembayaran Berhasil!</h2>
                <p class="lead mb-4">Selamat, Anda sekarang adalah member premium MoodBite!</p>
                
                <!-- Membership Details -->
                <div class="card mb-4" style="border: 2px solid #FFE6E6;">
                    <div class="card-body">
                        <h5 class="mb-3">Detail Membership</h5>
                        <div class="row text-start">
                            <div class="col-6 mb-2">
                                <strong>Paket:</strong>
                            </div>
                            <div class="col-6 mb-2">
                                {{ ucfirst($currentMembership->type) }}
                            </div>
                            
                            <div class="col-6 mb-2">
                                <strong>Berlaku Sampai:</strong>
                            </div>
                            <div class="col-6 mb-2">
                                {{ $currentMembership->end_date->format('d F Y') }}
                            </div>
                            
                            <div class="col-6 mb-2">
                                <strong>Order ID:</strong>
                            </div>
                            <div class="col-6 mb-2">
                                {{ $currentMembership->order_id }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Premium Badge -->
                <div class="mb-4">
                    <span class="badge" style="background: linear-gradient(135deg, #FFD700, #FFA500); 
                         color: #333; padding: 10px 20px; font-size: 1.1rem;">
                        <i class="fas fa-crown me-2"></i>MEMBER PREMIUM
                    </span>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('recommendations') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Cari Rekomendasi Premium
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary ms-md-2 mt-2 mt-md-0">
                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
                
                <!-- Features Reminder -->
                <div class="mt-4 text-muted small">
                    <p>Nikmati fitur premium Anda mulai sekarang:</p>
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <i class="fas fa-star me-1"></i>Rekomendasi Premium
                        </li>
                        <li class="list-inline-item">
                            <i class="fas fa-map-marked-alt me-1"></i>Detail Lengkap
                        </li>
                        <li class="list-inline-item">
                            <i class="fas fa-utensils me-1"></i>Resep Eksklusif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection