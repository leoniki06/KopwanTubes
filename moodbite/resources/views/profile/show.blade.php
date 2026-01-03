@extends('layouts.app')

@section('title', 'Profil Saya - MoodBite')

@section('content')
@php
    $user = Auth::user();

    $preferences = $user->food_preferences ?? [];
    if (is_string($preferences)) {
        $decoded = json_decode($preferences, true);
        $preferences = is_array($decoded) ? $decoded : [];
    }
    if (!is_array($preferences)) $preferences = [];

    $isAdmin = ($user->role ?? null) === 'admin';
    $isPremium = $user->isPremium();
    $premiumUntil = optional($user->premium_until)->format('d F Y');

    $history = $recommendationHistory ?? collect();
@endphp

<div class="container py-4">

    @if($isAdmin)
        <div class="admin-hero mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="admin-tag mb-2 d-inline-flex">
                        <i class="fas fa-shield-halved me-2"></i>ADMIN PROFILE
                    </span>
                    <h3 class="fw-bold mb-1 text-white">Halo, {{ $user->name }} 👋</h3>
                    <p class="text-white-50 mb-0">Kamu masuk sebagai Admin MoodBite, kelola user & membership di dashboard.</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-admin-primary">
                        <i class="fas fa-chart-line me-2"></i>Admin Dashboard
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-admin-outline">
                        <i class="fas fa-pen me-2"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="profile-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1">Profil Kamu</h3>
                    <p class="text-muted mb-0">Kelola akun & preferensi makananmu 🍓</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('recommendations') }}" class="btn btn-gradient">
                        <i class="fas fa-search me-2"></i>Cari Makanan
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-pink">
                        <i class="fas fa-pen me-2"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-soft border-0 profile-card">
                <div class="card-body p-4 text-center">
                    <div class="avatar-wrap mx-auto mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/avatars/' . $user->avatar) }}" class="avatar-img" alt="Avatar">
                        @else
                            <div class="avatar-fallback">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>

                    @if($isAdmin)
                        <div class="status-badge status-admin mb-2">
                            <i class="fas fa-shield-halved me-2"></i> Admin MoodBite
                        </div>
                        <div class="small text-muted">
                            Akses admin aktif tanpa membership premium
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-admin-primary w-100">
                                <i class="fas fa-arrow-right me-2"></i>Masuk Dashboard Admin
                            </a>
                        </div>
                    @else
                        @if($isPremium)
                            <div class="status-badge status-premium mb-2">
                                <i class="fas fa-crown me-2"></i> Premium Member
                            </div>
                            <div class="small text-muted">
                                Aktif sampai <span class="fw-semibold">{{ $premiumUntil }}</span>
                            </div>
                        @else
                            <div class="status-badge status-free mb-2">
                                <i class="fas fa-heart me-2"></i> Free Member
                            </div>
                            <div class="small text-muted">
                                Upgrade untuk akses resep eksklusif 💗
                            </div>
                        @endif
                    @endif

                    <div class="divider my-4"></div>

                    <div class="mini-info text-start">
                        <div class="mini-row">
                            <div class="mini-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div>
                                <div class="mini-label">Member Sejak</div>
                                <div class="mini-value">{{ optional($user->created_at)->format('d F Y') }}</div>
                            </div>
                        </div>

                        @if($user->phone)
                            <div class="mini-row mt-2">
                                <div class="mini-icon"><i class="fas fa-phone"></i></div>
                                <div>
                                    <div class="mini-label">Telepon</div>
                                    <div class="mini-value">{{ $user->formatted_phone ?? $user->phone }}</div>
                                </div>
                            </div>
                        @endif

                        @if($user->birthdate)
                            <div class="mini-row mt-2">
                                <div class="mini-icon"><i class="fas fa-birthday-cake"></i></div>
                                <div>
                                    <div class="mini-label">Tanggal Lahir</div>
                                    <div class="mini-value">{{ $user->birthdate->format('d F Y') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if(!$isAdmin)
            <div class="card card-soft border-0 mt-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-heart text-pink me-2"></i>Preferensi Makanan
                        </h5>
                        <span class="text-muted small">opsional</span>
                    </div>

                    <form action="{{ route('profile.preferences') }}" method="POST">
                        @csrf

                        <div class="pref-grid">
                            @foreach([
                                'vegetarian' => 'Vegetarian',
                                'vegan' => 'Vegan',
                                'halal' => 'Halal',
                                'spicy' => 'Suka Pedas',
                                'sweet' => 'Suka Manis',
                                'low_calorie' => 'Rendah Kalori'
                            ] as $key => $label)
                                <label class="pref-item">
                                    <input type="checkbox" name="preferences[]" value="{{ $key }}" {{ in_array($key, $preferences) ? 'checked' : '' }}>
                                    <span class="pref-chip">
                                        <span class="pref-dot"></span>
                                        <span class="pref-text">{{ $label }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-pink w-100 mt-3">
                            <i class="fas fa-save me-2"></i>Simpan Preferensi
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card card-soft border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-user-circle text-pink me-2"></i>Informasi Profil
                        </h5>
                        <span class="pill">
                            <i class="fas fa-shield-alt me-2"></i>Secure
                        </span>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Role</div>
                            <div class="info-value">
                                @if($isAdmin)
                                    <span class="status-pill admin">
                                        <i class="fas fa-shield-halved me-2"></i>ADMIN
                                    </span>
                                @else
                                    <span class="status-pill free">
                                        <i class="fas fa-user me-2"></i>USER
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Member Sejak</div>
                            <div class="info-value">{{ optional($user->created_at)->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-soft border-0 mt-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-history text-pink me-2"></i>Riwayat Rekomendasi
                        </h5>
                        <span class="text-muted small">terakhir kamu cari</span>
                    </div>

                    @if($history->count() > 0)
                        <div class="history-list">
                            @foreach($history as $item)
                                <div class="history-item">
                                    <div class="history-left">
                                        <div class="history-icon">
                                            <i class="fas fa-face-smile"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ ucfirst($item->mood) }}</div>
                                            <div class="text-muted small">{{ $item->created_at->format('d M Y • H:i') }}</div>
                                        </div>
                                    </div>

                                    <div class="history-right">
                                        <span class="history-pill">
                                            {{ $item->total_recommendations ?? 0 }} menu
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('recommendations') }}" class="btn btn-gradient px-4">
                                <i class="fas fa-search me-2"></i>Cari Lagi
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="mt-3">
                                <div class="fw-bold">Belum ada riwayat rekomendasi</div>
                                <div class="text-muted">Yuk cari makanan sesuai mood kamu sekarang ✨</div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('recommendations') }}" class="btn btn-gradient px-4">
                                    <i class="fas fa-search me-2"></i>Cari Rekomendasi
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="tip-card mt-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="tip-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div>
                        <div class="fw-bold mb-1">Biar rekomendasi makin akurat</div>
                        <div class="text-muted">
                            Isi preferensi makanan + pilih mood kamu setiap kali cari menu. Nanti hasilnya makin “ngena” 💗
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
:root{
    --pink:#FF6B8B;
    --pink2:#C8A2C8;
    --pink-soft:#FFF0F5;
    --text:#1f2937;
}

.text-pink{ color: var(--pink) !important; }

.profile-header{
    background: linear-gradient(135deg, rgba(255,107,139,.12), rgba(200,162,200,.12));
    border: 1px solid rgba(255,107,139,.15);
    border-radius: 18px;
    padding: 18px;
}

.admin-hero{
    background: linear-gradient(135deg, rgba(255,107,139,.98), rgba(200,162,200,.98));
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 20px 40px rgba(255,107,139,.25);
}

.admin-tag{
    background: rgba(255,255,255,.20);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    font-weight: 900;
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 999px;
    align-items: center;
}

.btn-admin-primary{
    background: linear-gradient(135deg, #FFD166, #FF6B8B);
    border: none;
    color: #fff;
    font-weight: 900;
    padding: 10px 16px;
    border-radius: 14px;
    box-shadow: 0 12px 25px rgba(0,0,0,.12);
}
.btn-admin-primary:hover{
    filter: brightness(.98);
    color: #fff;
}

.btn-admin-outline{
    background: transparent;
    border: 2px solid rgba(255,255,255,.75);
    color: #fff;
    font-weight: 900;
    padding: 10px 16px;
    border-radius: 14px;
}
.btn-admin-outline:hover{
    background: rgba(255,255,255,.15);
    color:#fff;
}

.card-soft{
    background: rgba(255,255,255,.92);
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(255,107,139,.08);
    backdrop-filter: blur(6px);
}

.profile-card{
    position: relative;
    overflow: hidden;
}
.profile-card::before{
    content:"";
    position:absolute;
    inset:0;
    background: radial-gradient(circle at 20% 0%, rgba(255,107,139,.25), transparent 55%),
                radial-gradient(circle at 100% 40%, rgba(200,162,200,.25), transparent 55%);
    pointer-events:none;
}

.divider{
    height: 1px;
    background: rgba(255,107,139,.18);
}

.btn-pink{
    background: var(--pink);
    border: none;
    color: #fff;
    border-radius: 12px;
    padding: 10px 18px;
    font-weight: 700;
}
.btn-pink:hover{
    background: #E05575;
    color: #fff;
}

.btn-outline-pink{
    border: 2px solid var(--pink);
    color: var(--pink);
    border-radius: 12px;
    padding: 10px 16px;
    font-weight: 700;
    background: transparent;
}
.btn-outline-pink:hover{
    background: var(--pink);
    color: #fff;
}

.btn-gradient{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    border: none;
    color:#fff;
    border-radius: 14px;
    padding: 10px 16px;
    font-weight: 800;
    box-shadow: 0 12px 25px rgba(255,107,139,.18);
}
.btn-gradient:hover{
    filter: brightness(.98);
    color:#fff;
}

.avatar-wrap{
    width: 110px;
    height: 110px;
    border-radius: 999px;
    padding: 4px;
    background: linear-gradient(135deg, var(--pink), var(--pink2));
}
.avatar-img{
    width: 100%;
    height: 100%;
    border-radius: 999px;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.9);
}
.avatar-fallback{
    width: 100%;
    height: 100%;
    border-radius: 999px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size: 46px;
    font-weight: 900;
    color:#fff;
    background: radial-gradient(circle at 30% 20%, rgba(255,255,255,.25), transparent 45%),
                linear-gradient(135deg, rgba(255,107,139,.95), rgba(200,162,200,.95));
    border: 3px solid rgba(255,255,255,.9);
}

.status-badge{
    display:inline-flex;
    align-items:center;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 800;
}
.status-premium{
    background: rgba(255,107,139,.14);
    color: var(--pink);
    border: 1px solid rgba(255,107,139,.28);
}
.status-free{
    background: rgba(107,114,128,.10);
    color: #374151;
    border: 1px solid rgba(107,114,128,.18);
}
.status-admin{
    background: rgba(255,209,102,.20);
    color: #9A6A00;
    border: 1px solid rgba(255,209,102,.40);
}

.mini-info .mini-row{
    display:flex;
    gap: 12px;
    align-items:center;
    padding: 12px 14px;
    border-radius: 14px;
    background: rgba(255,107,139,.08);
    border: 1px solid rgba(255,107,139,.18);
}
.mini-icon{
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display:flex;
    align-items:center;
    justify-content:center;
    color: var(--pink);
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(255,107,139,.18);
}
.mini-label{
    font-size: 12px;
    color: #6b7280;
}
.mini-value{
    font-weight: 800;
    color: var(--text);
}

.info-grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.info-item{
    padding: 14px;
    border-radius: 14px;
    border: 1px solid rgba(255,107,139,.12);
    background: rgba(255,255,255,.86);
}
.info-label{
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 4px;
}
.info-value{
    font-weight: 800;
    color: var(--text);
}

.pill{
    font-size: 12px;
    color: #6b7280;
    border: 1px solid rgba(0,0,0,.08);
    border-radius: 999px;
    padding: 6px 10px;
    background: rgba(255,255,255,.8);
}

.status-pill{
    display:inline-flex;
    align-items:center;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 900;
}
.status-pill.admin{
    background: rgba(255,209,102,.18);
    color: #9A6A00;
    border: 1px solid rgba(255,209,102,.35);
}
.status-pill.free{
    background: rgba(107,114,128,.10);
    color: #374151;
    border: 1px solid rgba(107,114,128,.18);
}

.pref-grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.pref-item{
    cursor:pointer;
    user-select:none;
    display:block;
}
.pref-item input{ display:none; }

.pref-chip{
    display:flex;
    align-items:center;
    gap:10px;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid rgba(255,107,139,.14);
    background: rgba(255,255,255,.86);
    transition: .2s ease;
}
.pref-dot{
    width: 10px;
    height: 10px;
    border-radius: 999px;
    background: rgba(255,107,139,.35);
}
.pref-text{
    font-weight: 800;
    font-size: 14px;
    color: #374151;
}
.pref-item input:checked + .pref-chip{
    background: rgba(255,107,139,.12);
    border-color: rgba(255,107,139,.28);
}
.pref-item input:checked + .pref-chip .pref-dot{
    background: var(--pink);
}

.empty-state{
    border-radius: 18px;
    border: 1px dashed rgba(255,107,139,.25);
    background: rgba(255,107,139,.05);
    padding: 34px 18px;
    text-align:center;
}
.empty-icon{
    width: 62px;
    height: 62px;
    border-radius: 18px;
    margin: 0 auto;
    display:flex;
    align-items:center;
    justify-content:center;
    color: var(--pink);
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(255,107,139,.18);
    font-size: 24px;
}

.tip-card{
    border-radius: 18px;
    padding: 18px;
    background: linear-gradient(135deg, rgba(255,107,139,.10), rgba(200,162,200,.10));
    border: 1px solid rgba(255,107,139,.14);
}
.tip-icon{
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(255,107,139,.18);
    color: var(--pink);
}

.history-list{
    display:flex;
    flex-direction:column;
    gap: 12px;
}
.history-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding: 14px 16px;
    border-radius: 16px;
    border: 1px solid rgba(255,107,139,.14);
    background: rgba(255,255,255,.86);
    transition: .2s ease;
}
.history-item:hover{
    background: rgba(255,107,139,.07);
}
.history-left{
    display:flex;
    align-items:center;
    gap: 12px;
}
.history-icon{
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: rgba(255,107,139,.12);
    color: var(--pink);
}
.history-pill{
    font-size: 12px;
    font-weight: 900;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(255,107,139,.12);
    border: 1px solid rgba(255,107,139,.22);
    color: var(--pink);
}

@media (max-width: 768px){
    .info-grid{ grid-template-columns: 1fr; }
    .pref-grid{ grid-template-columns: 1fr; }
}
</style>
@endsection
