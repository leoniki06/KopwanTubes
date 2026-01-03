@extends('layouts.app')

@section('title', 'Preview Resep - MoodBite')

@section('content')
@php
    $data = $previewData;
@endphp

<div class="container py-5">
    <div class="card preview-card border-0">
        <div class="card-body p-5 text-center">

            <div class="badge-premium mb-3">
                <i class="fas fa-crown me-2"></i>PREVIEW PREMIUM
            </div>

            <h2 class="fw-bold mb-2">{{ $data['recipe_name'] }}</h2>
            <p class="text-muted mb-3">
                oleh <span class="fw-semibold">{{ $data['chef_name'] }}</span>
            </p>

            <div class="d-flex justify-content-center gap-2 flex-wrap mb-4">
                <span class="info-pill">
                    <i class="fas fa-clock me-2"></i>{{ $data['cooking_time'] }} menit
                </span>
                <span class="info-pill">
                    <i class="fas fa-star me-2"></i>{{ ucfirst($data['difficulty']) }}
                </span>
            </div>

            <p class="desc-preview mx-auto">
                {{ $data['description'] }}
            </p>

            <div class="divider my-4"></div>

            <h5 class="fw-bold mb-2 text-pink">
                Mau lihat resep lengkap?
            </h5>
            <p class="text-muted mb-4">
                Upgrade ke Premium untuk akses langkah memasak lengkap, bahan detail, dan resep eksklusif lainnya.
            </p>

            <a href="{{ route('membership.index') }}" class="btn btn-premium px-5">
                <i class="fas fa-crown me-2"></i>Upgrade Premium
            </a>

            <div class="mt-4">
                <a href="{{ route('premium.recipes.index') }}" class="btn btn-outline-pink px-4">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

        </div>
    </div>
</div>

<style>
:root{
    --pink:#FF6B8B;
    --lilac:#C8A2C8;
    --gold:#FFD166;
}

.preview-card{
    border-radius: 22px;
    background: rgba(255,255,255,.95);
    box-shadow: 0 20px 60px rgba(255,107,139,.12);
    overflow: hidden;
    position: relative;
}

.preview-card::before{
    content:"";
    position:absolute;
    inset:0;
    background: radial-gradient(circle at 20% 0%, rgba(255,107,139,.20), transparent 55%),
                radial-gradient(circle at 100% 45%, rgba(200,162,200,.22), transparent 55%);
    pointer-events:none;
}

.badge-premium{
    display:inline-flex;
    align-items:center;
    padding: 10px 18px;
    border-radius: 999px;
    font-weight: 900;
    color: #fff;
    background: linear-gradient(135deg, var(--pink), var(--gold));
    box-shadow: 0 10px 25px rgba(255,107,139,.25);
}

.info-pill{
    display:inline-flex;
    align-items:center;
    padding: 10px 14px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 13px;
    color: #374151;
    background: rgba(255,107,139,.10);
    border: 1px solid rgba(255,107,139,.18);
}

.desc-preview{
    max-width: 650px;
    font-size: 15px;
    color:#444;
    line-height: 1.7;
}

.divider{
    height: 1px;
    width: 100%;
    background: rgba(255,107,139,.18);
}

.text-pink{ color: var(--pink) !important; }

.btn-premium{
    background: linear-gradient(135deg, var(--pink), var(--gold));
    border:none;
    color:#fff;
    font-weight: 900;
    padding: 12px 18px;
    border-radius: 14px;
    box-shadow: 0 12px 30px rgba(255,107,139,.25);
}
.btn-premium:hover{
    filter: brightness(.98);
    color:#fff;
}

.btn-outline-pink{
    border: 2px solid var(--pink);
    color: var(--pink);
    border-radius: 14px;
    font-weight: 900;
    padding: 10px 16px;
}
.btn-outline-pink:hover{
    background: var(--pink);
    color:#fff;
}
</style>
@endsection
