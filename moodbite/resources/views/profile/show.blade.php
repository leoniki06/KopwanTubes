@extends('layouts.app')

@section('title', 'Profil Saya - MoodBite')

@section('content')
@php
    $user = Auth::user();

    $isAdmin = ($user->role ?? null) === 'admin';
    $isPremium = $user->isPremium();
    $premiumUntil = optional($user->premium_until)->format('d F Y');

    $history = $recommendationHistory ?? collect();

    $moodIcons = [
        'happy' => 'fa-smile-beam',
        'sad' => 'fa-sad-tear',
        'energetic' => 'fa-bolt',
        'stress' => 'fa-wind',
        'romantic' => 'fa-heart',
        'hungry' => 'fa-hamburger',
        'anxious' => 'fa-face-flushed',
        'lazy' => 'fa-couch',
        'rainy' => 'fa-cloud-rain',
        'focus' => 'fa-bullseye',
        'sleepy' => 'fa-moon',
        'party' => 'fa-champagne-glasses',
    ];

    $topMood = null;
    $topMoodCount = 0;
    $lastMood = null;
    $totalSearch = $history->count();
    $badge = "-";
    $badgeDesc = "";

    if($history->count() > 0){
        $lastMood = $history->first()->mood;

        $counts = $history->groupBy('mood')->map(fn($items) => $items->count());
        $topMood = $counts->sortDesc()->keys()->first();
        $topMoodCount = $counts->sortDesc()->values()->first();

        if($totalSearch >= 6){
            $badge = "Mood Explorer";
            $badgeDesc = "Kamu aktif banget cari rekomendasi!";
        }elseif($totalSearch >= 3){
            $badge = "Mood Seeker";
            $badgeDesc = "Kamu cukup aktif eksplor mood.";
        }else{
            $badge = "Newbie";
            $badgeDesc = "Mulai eksplor mood kamu ✨";
        }
    }
@endphp

<div class="container py-5">

    <div class="profile-hero mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="hero-pill">
                    <i class="fas fa-user me-2"></i> PROFILE DASHBOARD
                </span>

                @if($isAdmin)
                    <h2 class="fw-black mt-3 mb-1 text-white">Halo, {{ $user->name }} 👋</h2>
                    <p class="text-white-50 mb-0">Kamu masuk sebagai Admin MoodBite, kelola sistem & user.</p>
                @else
                    <h2 class="fw-black mt-3 mb-1 text-white">Profil Kamu ✨</h2>
                    <p class="text-white-50 mb-0">Lihat status membership dan riwayat rekomendasi kamu di sini.</p>
                @endif
            </div>

            <div class="d-flex gap-2 flex-wrap">
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-hero-primary">
                        <i class="fas fa-chart-line me-2"></i> Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('recommendations') }}" class="btn btn-hero-primary">
                        <i class="fas fa-search me-2"></i> Cari Makanan
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="btn btn-hero-outline">
                    <i class="fas fa-pen me-2"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="card card-soft border-0 rounded-5 shadow-sm overflow-hidden">
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

                    <h4 class="fw-black mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    @if($isAdmin)
                        <div class="status-badge admin mb-2">
                            <i class="fas fa-shield-halved me-2"></i> ADMIN MODE
                        </div>
                        <div class="text-muted small">
                            Kamu punya akses admin tanpa membership premium.
                        </div>

                        <a href="{{ route('admin.dashboard') }}" class="btn btn-hero-primary w-100 mt-3">
                            <i class="fas fa-arrow-right me-2"></i> Masuk Dashboard Admin
                        </a>
                    @else
                        @if($isPremium)
                            <div class="status-badge premium mb-2">
                                <i class="fas fa-crown me-2"></i> PREMIUM MEMBER
                            </div>
                            <div class="text-muted small">
                                Aktif sampai <b>{{ $premiumUntil }}</b>
                            </div>
                        @else
                            <div class="status-badge free mb-2">
                                <i class="fas fa-heart me-2"></i> FREE MEMBER
                            </div>
                            <div class="text-muted small">
                                Upgrade untuk akses fitur premium 💗
                            </div>

                            <a href="{{ route('membership.index') }}" class="btn btn-hero-primary w-100 mt-3">
                                <i class="fas fa-gem me-2"></i> Upgrade Premium
                            </a>
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
                    </div>
                </div>
            </div>

            @if(!$isAdmin && $history->count() > 0)
            <div class="card card-soft border-0 rounded-5 shadow-sm mt-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-black mb-0">
                            <i class="fas fa-chart-pie me-2 text-accent"></i> Mood & Activity Insights
                        </h6>
                        <span class="text-muted small">ringkas</span>
                    </div>

                    <div class="insight-grid">
                        <div class="insight-item">
                            <div class="insight-icon"><i class="fas fa-fire"></i></div>
                            <div>
                                <div class="insight-label">Mood Dominan</div>
                                <div class="insight-value">{{ $topMood ? ucfirst($topMood) : '-' }}</div>
                                <div class="insight-sub">{{ $topMoodCount }}x dipilih</div>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon"><i class="fas fa-clock"></i></div>
                            <div>
                                <div class="insight-label">Mood Terakhir</div>
                                <div class="insight-value">{{ $lastMood ? ucfirst($lastMood) : '-' }}</div>
                                <div class="insight-sub">terakhir kamu cari</div>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon"><i class="fas fa-magnifying-glass"></i></div>
                            <div>
                                <div class="insight-label">Total Aktivitas</div>
                                <div class="insight-value">{{ $totalSearch }}</div>
                                <div class="insight-sub">dari 6 terakhir</div>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon"><i class="fas fa-award"></i></div>
                            <div>
                                <div class="insight-label">Badge Kamu</div>
                                <div class="insight-value">{{ $badge }}</div>
                                <div class="insight-sub">{{ $badgeDesc }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif

        </div>

        <div class="col-lg-8">

            {{-- DETAIL AKUN (DI ATAS RIWAYAT) --}}
            <div class="card card-soft border-0 rounded-5 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-black mb-0">
                            <i class="fas fa-id-card me-2 text-accent"></i> Detail Akun
                        </h5>
                        <span class="secure-pill">
                            <i class="fas fa-lock me-2"></i> Secure
                        </span>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Nama</div>
                            <div class="detail-value">{{ $user->name }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $user->email }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Role</div>
                            <div class="detail-value">
                                @if($isAdmin)
                                    <span class="role-pill admin"><i class="fas fa-shield-halved me-2"></i>ADMIN</span>
                                @else
                                    <span class="role-pill user"><i class="fas fa-user me-2"></i>USER</span>
                                @endif
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Membership</div>
                            <div class="detail-value">
                                @if($isAdmin)
                                    <span class="role-pill admin"><i class="fas fa-shield-halved me-2"></i>ADMIN ACCESS</span>
                                @else
                                    @if($isPremium)
                                        <span class="role-pill premium"><i class="fas fa-crown me-2"></i>PREMIUM</span>
                                    @else
                                        <span class="role-pill user"><i class="fas fa-heart me-2"></i>FREE</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if(!$isAdmin && $isPremium)
                        <div class="detail-item">
                            <div class="detail-label">Premium Aktif Sampai</div>
                            <div class="detail-value">{{ $premiumUntil }}</div>
                        </div>
                        @endif

                        <div class="detail-item">
                            <div class="detail-label">Member Sejak</div>
                            <div class="detail-value">{{ optional($user->created_at)->format('d F Y') }}</div>
                        </div>

                        @if($user->phone)
                        <div class="detail-item">
                            <div class="detail-label">Telepon</div>
                            <div class="detail-value">{{ $user->formatted_phone ?? $user->phone }}</div>
                        </div>
                        @endif

                        @if($user->address)
                        <div class="detail-item">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value">{{ $user->address }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIWAYAT --}}
            <div class="card card-soft border-0 rounded-5 shadow-sm mt-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-black mb-0">
                            <i class="fas fa-history me-2 text-accent"></i> Riwayat Rekomendasi
                        </h5>
                        <span class="text-muted small">6 terakhir</span>
                    </div>

                    @if($history->count() > 0)
                        <div class="history-list">
                            @foreach($history as $item)
                                @php
                                    $r = $item->results;
                                    if(is_string($r)) $r = json_decode($r, true);
                                    $total = is_array($r) ? count($r) : 0;
                                @endphp

                                <div class="history-item">
                                    <div class="history-left">
                                        <div class="history-icon">
                                            <i class="fas {{ $moodIcons[$item->mood] ?? 'fa-face-smile' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ ucfirst($item->mood) }}</div>
                                            <div class="text-muted small">{{ $item->created_at->format('d M Y • H:i') }}</div>
                                        </div>
                                    </div>

                                    <span class="count-pill">
                                        {{ $total }} menu
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('recommendations') }}" class="btn btn-hero-primary px-4">
                                <i class="fas fa-search me-2"></i> Cari Lagi
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <h6 class="fw-black mt-3">Belum ada riwayat rekomendasi</h6>
                            <p class="text-muted mb-4">Yuk cari makanan sesuai mood kamu sekarang ✨</p>
                            <a href="{{ route('recommendations') }}" class="btn btn-hero-primary px-4">
                                <i class="fas fa-search me-2"></i> Cari Rekomendasi
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<style>
:root{
    --accent:#FF6B8B;
    --bg:#F9FAFB;
    --card:#FFFFFF;
    --text:#111827;
}

body{ background: var(--bg) !important; }
.text-accent{ color: var(--accent) !important; }
.fw-black{ font-weight: 900; }

.profile-hero{
    border-radius: 24px;
    padding: 22px;
    background: linear-gradient(135deg, var(--accent), #FFB6C8);
    box-shadow: 0 18px 35px rgba(255,107,139,.18);
}

.hero-pill{
    display:inline-flex; align-items:center;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 900; font-size: 12px;
    color: #fff;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.25);
}

.card-soft{
    background: var(--card);
    border: 1px solid rgba(0,0,0,.05);
}

.btn-hero-primary{
    background: #fff; border:none;
    padding: 10px 16px;
    border-radius: 14px;
    font-weight: 900;
    color: var(--text);
    transition: .2s ease;
}
.btn-hero-primary:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(0,0,0,.10);
}

.btn-hero-outline{
    background: rgba(255,255,255,.12);
    border: 2px solid rgba(255,255,255,.45);
    padding: 10px 16px;
    border-radius: 14px;
    font-weight: 900;
    color:#fff;
}
.btn-hero-outline:hover{ background: rgba(255,255,255,.22); }

.avatar-wrap{
    width: 110px; height: 110px;
    padding: 4px;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--accent), #FFB6C8);
}
.avatar-img{
    width:100%; height:100%;
    border-radius: 999px;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.9);
}
.avatar-fallback{
    width:100%; height:100%;
    border-radius:999px;
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size: 46px; font-weight: 900;
    background: linear-gradient(135deg, var(--accent), #FFB6C8);
}

.status-badge{
    display:inline-flex; align-items:center;
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 900;
}
.status-badge.premium{
    background: rgba(255,215,102,.22);
    border: 1px solid rgba(255,215,102,.30);
    color:#8A6100;
}
.status-badge.free{
    background: rgba(255,107,139,.10);
    border: 1px solid rgba(255,107,139,.16);
    color: var(--accent);
}
.status-badge.admin{
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.20);
    color:#166534;
}

.divider{ height: 1px; background: rgba(0,0,0,.07); }

.mini-row{
    display:flex; gap: 12px; align-items:center;
    padding: 12px 14px;
    border-radius: 16px;
    background: rgba(255,107,139,.05);
    border: 1px solid rgba(255,107,139,.10);
}
.mini-icon{
    width: 38px; height: 38px;
    display:flex; align-items:center; justify-content:center;
    border-radius: 14px;
    background: rgba(255,107,139,.10);
    color: var(--accent);
}
.mini-label{ font-size: 12px; color: #6b7280; }
.mini-value{ font-weight: 900; }

.history-list{ display:flex; flex-direction:column; gap: 12px; }
.history-item{
    display:flex; align-items:center; justify-content:space-between;
    padding: 14px 16px;
    border-radius: 18px;
    background: rgba(255,107,139,.04);
    border: 1px solid rgba(255,107,139,.10);
    transition: .2s ease;
}
.history-item:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(0,0,0,.06);
}
.history-left{ display:flex; align-items:center; gap: 12px; }
.history-icon{
    width: 44px; height: 44px;
    border-radius: 16px;
    display:flex; align-items:center; justify-content:center;
    background: rgba(255,107,139,.12);
    color: var(--accent);
}
.count-pill{
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 12px;
    background: rgba(255,107,139,.12);
    border: 1px solid rgba(255,107,139,.16);
    color: var(--accent);
}

.empty-state{
    padding: 40px 20px;
    text-align:center;
    border-radius: 20px;
    background: rgba(255,107,139,.05);
    border: 1px dashed rgba(255,107,139,.20);
}
.empty-icon{
    width: 64px; height: 64px;
    border-radius: 18px;
    display:flex; align-items:center; justify-content:center;
    margin: 0 auto;
    background: rgba(255,107,139,.12);
    color: var(--accent);
    font-size: 26px;
}

.insight-grid{ display:grid; grid-template-columns: 1fr; gap: 12px; }
.insight-item{
    display:flex; gap: 12px; align-items:center;
    padding: 14px 14px;
    border-radius: 18px;
    background: rgba(255,107,139,.04);
    border: 1px solid rgba(255,107,139,.10);
}
.insight-icon{
    width: 44px; height: 44px;
    border-radius: 18px;
    display:flex; align-items:center; justify-content:center;
    background: rgba(255,107,139,.12);
    color: var(--accent);
    font-size: 16px;
}
.insight-label{ font-size: 12px; color:#6b7280; font-weight: 700; }
.insight-value{ font-size: 15px; font-weight: 900; color: var(--text); }
.insight-sub{ font-size: 12px; color:#9ca3af; }

.secure-pill{
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(0,0,0,.04);
    border: 1px solid rgba(0,0,0,.06);
    color: #6b7280;
    font-weight: 800;
}

.detail-grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.detail-item{
    padding: 14px 14px;
    border-radius: 18px;
    background: rgba(255,107,139,.03);
    border: 1px solid rgba(255,107,139,.08);
}
.detail-label{
    font-size: 12px;
    color:#6b7280;
    font-weight: 700;
    margin-bottom: 4px;
}
.detail-value{
    font-weight: 900;
    color: var(--text);
}
.role-pill{
    display:inline-flex;
    align-items:center;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 12px;
    border: 1px solid rgba(0,0,0,.06);
}
.role-pill.user{
    background: rgba(255,107,139,.10);
    color: var(--accent);
    border-color: rgba(255,107,139,.14);
}
.role-pill.premium{
    background: rgba(255,215,102,.22);
    color:#8A6100;
    border-color: rgba(255,215,102,.30);
}
.role-pill.admin{
    background: rgba(34,197,94,.12);
    color:#166534;
    border-color: rgba(34,197,94,.20);
}

@media (max-width: 768px){
    .detail-grid{ grid-template-columns: 1fr; }
}
</style>
@endsection
