@extends('layouts.app')

@section('title', 'Kelola Resep Premium - MoodBite')

@section('content')
@php
    $filters = $filters ?? [
        'mood' => request('mood'),
        'difficulty' => request('difficulty'),
        'active' => request('active')
    ];

    $moods = $moods ?? collect();
    $difficulties = $difficulties ?? collect();

    $totalRecipes = $recipes->total() ?? 0;
    $activeCount = \App\Models\PremiumRecipe::where('is_active', true)->count();
    $inactiveCount = \App\Models\PremiumRecipe::where('is_active', false)->count();
@endphp

<div class="container py-4 admin-premium-wrap">

    {{-- HERO --}}
    <div class="hero-admin rounded-4 p-4 mb-4 shadow-sm">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="hero-pill">
                    <i class="fas fa-utensils me-2"></i>Kelola Resep Premium
                </span>
                <h2 class="fw-bold mt-3 mb-1 text-white">Dashboard Resep Premium ✨</h2>
                <p class="text-white-50 mb-0">
                    Tambah, edit, hapus, dan aktif/nonaktifkan resep premium.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.premium-recipes.create') }}" class="btn btn-hero">
                    <i class="fas fa-plus me-2"></i>Tambah Resep
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-hero-outline">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success rounded-4 shadow-sm border-0">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif


    {{-- STAT SUMMARY (BIAR DASHBOARD VIBES) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="icon-box bg-soft-red">
                            <i class="fas fa-book text-danger"></i>
                        </div>
                        <span class="badge bg-soft-red text-danger fw-bold rounded-pill px-3 py-2">TOTAL</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $totalRecipes }}</h3>
                    <p class="text-muted mb-0">Total resep premium tersedia</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="icon-box bg-soft-green">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <span class="badge bg-soft-green text-success fw-bold rounded-pill px-3 py-2">AKTIF</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $activeCount }}</h3>
                    <p class="text-muted mb-0">Resep masih bisa diakses user</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="icon-box bg-soft-red">
                            <i class="fas fa-ban text-danger"></i>
                        </div>
                        <span class="badge bg-soft-red text-danger fw-bold rounded-pill px-3 py-2">NONAKTIF</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $inactiveCount }}</h3>
                    <p class="text-muted mb-0">Tidak muncul ke user premium</p>
                </div>
            </div>
        </div>
    </div>


    {{-- FILTER --}}
    <div class="card border-0 rounded-4 shadow-sm mb-4 filter-card">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="small text-muted fw-semibold">Filter Mood</label>
                    <select name="mood" class="form-select rounded-4">
                        <option value="">Semua Mood</option>
                        @foreach($moods as $m)
                            <option value="{{ $m }}" {{ ($filters['mood'] == $m) ? 'selected' : '' }}>
                                {{ ucfirst($m) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="small text-muted fw-semibold">Filter Kesulitan</label>
                    <select name="difficulty" class="form-select rounded-4">
                        <option value="">Semua Level</option>
                        @foreach($difficulties as $d)
                            <option value="{{ $d }}" {{ ($filters['difficulty'] == $d) ? 'selected' : '' }}>
                                {{ ucfirst($d) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-filter w-100 rounded-4">
                        <i class="fas fa-filter me-2"></i>Terapkan
                    </button>
                    <a href="{{ route('admin.premium-recipes.index') }}" class="btn btn-reset w-100 rounded-4">
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>


    {{-- LIST CARD --}}
    <div class="row g-4 align-items-stretch">
        @forelse($recipes as $recipe)
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="recipe-card w-100">

                    {{-- HEADER --}}
                    <div class="recipe-thumb">
                        <div class="thumb-overlay"></div>

                        <span class="recipe-status {{ $recipe->is_active ? 'active' : 'inactive' }}">
                            {{ $recipe->is_active ? 'AKTIF' : 'NONAKTIF' }}
                        </span>

                        <span class="mood-pill">
                            {{ ucfirst($recipe->mood_category ?? '-') }}
                        </span>
                    </div>

                    {{-- BODY --}}
                    <div class="recipe-body p-4">
                        <h5 class="recipe-title fw-bold mb-1">
                            {{ $recipe->recipe_name }}
                        </h5>

                        <div class="text-muted small mb-3">
                            <i class="fas fa-user me-1"></i>{{ $recipe->chef_name }}
                        </div>

                        <div class="recipe-meta">
                            <span class="meta-pill">
                                <i class="fas fa-clock me-1"></i>{{ $recipe->cooking_time }} menit
                            </span>
                            <span class="meta-pill">
                                <i class="fas fa-signal me-1"></i>{{ ucfirst($recipe->difficulty) }}
                            </span>
                            <span class="meta-pill">
                                <i class="fas fa-users me-1"></i>{{ $recipe->servings }} porsi
                            </span>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="action-row mt-auto d-flex justify-content-end gap-2 pt-3">

                            <a href="{{ route('admin.premium-recipes.edit', $recipe->id) }}" class="btn icon-btn edit-btn">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.premium-recipes.toggle', $recipe->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn icon-btn toggle-btn">
                                    <i class="fas {{ $recipe->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.premium-recipes.destroy', $recipe->id) }}"
                                  onsubmit="return confirm('Yakin hapus resep ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn icon-btn delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-card text-center py-5 rounded-4 shadow-sm">
                    <div class="empty-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h5 class="fw-bold mt-3 mb-1">Belum ada resep premium</h5>
                    <p class="text-muted mb-4">Tambahkan resep premium pertama kamu sekarang.</p>
                    <a href="{{ route('admin.premium-recipes.create') }}" class="btn btn-hero">
                        <i class="fas fa-plus me-2"></i>Tambah Resep
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $recipes->links() }}
    </div>
</div>


<style>
:root{
    --pink:#FF6B8B;
    --pink2:#C8A2C8;
    --soft:#FFF0F5;
    --shadow: 0 18px 45px rgba(255,107,139,.14);
    --shadow2: 0 10px 30px rgba(0,0,0,.08);
}

/* HERO */
.hero-admin{
    background: linear-gradient(135deg, rgba(255,107,139,.95), rgba(200,162,200,.95));
    box-shadow: 0 20px 40px rgba(255,107,139,.20);
}
.hero-pill{
    display:inline-flex;
    align-items:center;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 13px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
}

.btn-hero{
    background: linear-gradient(135deg, #FFD166, #FF6B8B);
    border:none;
    color:#fff;
    font-weight:900;
    padding: 10px 18px;
    border-radius: 14px;
}
.btn-hero:hover{ filter:brightness(.96); color:#fff; }

.btn-hero-outline{
    border:2px solid rgba(255,255,255,.75);
    background: transparent;
    color:#fff;
    font-weight:900;
    padding: 10px 18px;
    border-radius: 14px;
}
.btn-hero-outline:hover{ background: rgba(255,255,255,.12); color:#fff; }

/* STATS */
.stat-card{ transition:.25s ease; }
.stat-card:hover{ transform: translateY(-4px); box-shadow: var(--shadow); }
.icon-box{
    width: 48px; height: 48px;
    border-radius: 16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size: 1.2rem;
}
.bg-soft-pink{ background: rgba(255,107,139,.10); }
.bg-soft-green{ background: rgba(25,135,84,.12); }
.bg-soft-red{ background: rgba(220,53,69,.12); }

/* FILTER */
.filter-card{
    background: rgba(255,255,255,.92);
    border-radius: 18px;
    box-shadow: var(--shadow2);
}
.btn-filter{
    background: var(--pink);
    color:#fff;
    border:none;
    font-weight:800;
}
.btn-filter:hover{ background:#E05575; color:#fff; }

.btn-reset{
    border: 2px solid rgba(255,107,139,.35);
    background: transparent;
    color: var(--pink);
    font-weight: 800;
}
.btn-reset:hover{ background: rgba(255,107,139,.10); }

/* CARD RESEP */
.recipe-card{
    border-radius: 22px;
    overflow: hidden;
    background: #fff;
    border: 1px solid rgba(255,107,139,.12);
    box-shadow: var(--shadow2);
    transition:.25s ease;
    display:flex;
    flex-direction: column;
    height:100%;
}
.recipe-card:hover{
    transform: translateY(-6px);
    box-shadow: var(--shadow);
}
.recipe-thumb{
    height: 150px;
    background: linear-gradient(135deg, rgba(255,107,139,.85), rgba(200,162,200,.85));
    position: relative;
}
.thumb-overlay{
    position:absolute;
    inset:0;
    background: radial-gradient(circle at top left, rgba(255,255,255,.25), transparent 70%);
}

/* BADGES */
.recipe-status{
    position:absolute;
    top: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 12px;
    color:#fff;
}
.recipe-status.active{ background: rgba(25,135,84,.95); }
.recipe-status.inactive{ background: rgba(220,53,69,.95); }

.mood-pill{
    position:absolute;
    left: 12px;
    bottom: 12px;
    padding: 6px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(255,107,139,.25);
    color: var(--pink);
    font-weight: 900;
    font-size: 12px;
}

/* BODY */
.recipe-body{
    flex:1;
    display:flex;
    flex-direction: column;
}
.recipe-title{
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 48px;
}
.recipe-meta{
    display:flex;
    flex-wrap:wrap;
    gap: 10px;
}
.meta-pill{
    font-size: 13px;
    font-weight: 700;
    padding: 8px 12px;
    border-radius: 999px;
    border: 1px solid rgba(255,107,139,.18);
    background: rgba(255,107,139,.05);
    color: rgba(0,0,0,.7);
}

/* ACTION BUTTONS */
.icon-btn{
    width: 44px;
    height: 44px;
    border-radius: 16px;
    border: none;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:.2s;
}
.icon-btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(255,107,139,.14);
}
.edit-btn{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    color:#fff;
}
.toggle-btn{
    border: 2px solid rgba(255,107,139,.25);
    background: rgba(255,107,139,.10);
    color: var(--pink);
}
.delete-btn{
    border: 2px solid rgba(220,53,69,.25);
    background: rgba(220,53,69,.12);
    color: #dc3545;
}

/* EMPTY */
.empty-card{
    background: rgba(255,107,139,.05);
    border: 1px dashed rgba(255,107,139,.25);
}
.empty-icon{
    width: 74px;
    height: 74px;
    border-radius: 22px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin: 0 auto;
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(255,107,139,.15);
    color: var(--pink);
    font-size: 26px;
}
</style>
@endsection
