@extends('layouts.app')

@section('title', 'Admin Dashboard - MoodBite')

@section('content')
@php
    $latestUsers = isset($users) ? $users->take(10) : collect();

    $premiumUsers = $totalPremium ?? 0;
    $nonPremiumUsers = ($totalUsers ?? 0) - $premiumUsers;

    $chartLabels = $labels ?? [];
    $chartVisitorValues = $visitorValues ?? [];
    $chartLoginValues = $loginValues ?? [];

    // finance (kalau belum ada dari controller, aman)
    $totalRevenue = $totalRevenue ?? 0;
    $revenueToday = $revenueToday ?? 0;
    $revenueThisWeek = $revenueThisWeek ?? 0;
@endphp

<div class="admin-shell py-4">

    {{-- ✅ HEADER --}}
    <div class="topbar mb-4">
        <div>
            <h2 class="title">Admin Dashboard</h2>
            <p class="subtitle">Ringkasan user, aktivitas, dan pendapatan MoodBite.</p>
        </div>

        <div class="topbar-right">
            <span class="pill">
                <i class="fas fa-calendar-alt me-2"></i> {{ now()->format('d M Y') }}
            </span>
            <span class="pill soft">
                <i class="fas fa-shield-halved me-2"></i> Admin Mode
            </span>
        </div>
    </div>

    {{-- ✅ STATS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="mini-card">
                <div class="mini-top">
                    <span class="mini-icon bg-soft"><i class="fas fa-users"></i></span>
                    <span class="mini-label">TOTAL USERS</span>
                </div>
                <div class="mini-value">{{ $totalUsers ?? 0 }}</div>
                <div class="mini-desc">Akun terdaftar</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card">
                <div class="mini-top">
                    <span class="mini-icon bg-gold"><i class="fas fa-crown"></i></span>
                    <span class="mini-label">PREMIUM ACTIVE</span>
                </div>
                <div class="mini-value">{{ $premiumUsers }}</div>
                <div class="mini-desc">Member premium aktif</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card">
                <div class="mini-top">
                    <span class="mini-icon bg-blue"><i class="fas fa-user"></i></span>
                    <span class="mini-label">FREE USERS</span>
                </div>
                <div class="mini-value">{{ $nonPremiumUsers }}</div>
                <div class="mini-desc">Belum upgrade</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card highlight">
                <div class="mini-top">
                    <span class="mini-icon bg-mint"><i class="fas fa-wallet"></i></span>
                    <span class="mini-label">TOTAL REVENUE</span>
                </div>
                <div class="mini-value">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
                <div class="mini-desc">Pendapatan membership</div>
            </div>
        </div>
    </div>


    {{-- ✅ MAIN GRID --}}
    <div class="row g-4 mb-4">

        {{-- Chart --}}
        <div class="col-lg-8">
            <div class="panel h-100">
                <div class="panel-head">
                    <h5 class="panel-title">
                        <i class="fas fa-chart-line me-2"></i> Aktivitas (7 Hari Terakhir)
                    </h5>
                    <span class="chip">Pengunjung vs Login</span>
                </div>

                <div class="panel-body chart-box">
                    <canvas id="activityChart" height="120"></canvas>
                </div>

                <div class="panel-foot">
                    <span class="dot"></span>
                    Pengunjung dihitung dari IP unik per hari, login dari jumlah login per hari.
                </div>
            </div>
        </div>

        {{-- Action + Finance --}}
        <div class="col-lg-4">
            <div class="panel h-100">
                <div class="panel-head">
                    <h5 class="panel-title">
                        <i class="fas fa-bolt me-2"></i> Quick Menu
                    </h5>
                </div>

                <div class="panel-body">
                    <div class="menu-stack">
                        <a href="{{ route('admin.memberships') }}" class="menu-btn">
                            <div class="left">
                                <span class="icon bg-gold"><i class="fas fa-crown"></i></span>
                                <span>Membership Premium</span>
                            </div>
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        <a href="{{ route('admin.premium-recipes.index') }}" class="menu-btn">
                            <div class="left">
                                <span class="icon bg-soft"><i class="fas fa-utensils"></i></span>
                                <span>Kelola Resep Premium</span>
                            </div>
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        <a href="{{ route('dashboard') }}" class="menu-btn outline">
                            <div class="left">
                                <span class="icon bg-blue"><i class="fas fa-user"></i></span>
                                <span>Dashboard User</span>
                            </div>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="finance-box mt-4">
                        <div class="finance-title">
                            <i class="fas fa-coins me-2"></i> Revenue Snapshot
                        </div>

                        <div class="finance-row">
                            <span>Hari ini</span>
                            <strong>Rp {{ number_format($revenueToday,0,',','.') }}</strong>
                        </div>

                        <div class="finance-row">
                            <span>Minggu ini</span>
                            <strong>Rp {{ number_format($revenueThisWeek,0,',','.') }}</strong>
                        </div>

                        <div class="finance-note">
                            * hanya transaksi membership yang statusnya paid
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- ✅ TABLE USERS --}}
    <div class="panel">
        <div class="panel-head">
            <h5 class="panel-title">
                <i class="fas fa-user-clock me-2"></i> User Terbaru
            </h5>
            <span class="chip">Realtime</span>
        </div>

        <div class="panel-body table-responsive">
            <table class="table modern-table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Premium</th>
                        <th>Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestUsers as $u)
                        <tr>
                            <td class="fw-bold">{{ $u->id }}</td>

                            <td>
                                <div class="user-box">
                                    <div class="avatar">
                                        {{ strtoupper(substr($u->name,0,1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $u->name }}</div>
                                        <div class="small text-muted">{{ strtoupper($u->role ?? 'user') }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-muted">{{ $u->email }}</td>

                            <td>
                                @if($u->premium_until && $u->premium_until >= now())
                                    <span class="badge premium">
                                        <i class="fas fa-crown me-1"></i> Premium
                                    </span>
                                @else
                                    <span class="badge free">Free</span>
                                @endif
                            </td>

                            <td class="text-muted small">
                                {{ optional($u->created_at)->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


</div>


{{-- ✅ STYLE --}}
<style>
:root{
    --bg: #f7f5f2;
    --card: rgba(255,255,255,.88);
    --border: rgba(15, 23, 42, .08);
    --text: #1f2937;
    --muted: rgba(31,41,55,.55);

    --accent: #ff6b8b;
    --accent2: #c8a2c8;

    --soft: rgba(255,107,139,.10);
    --gold: rgba(255,209,102,.20);
    --blue: rgba(135,206,235,.25);
    --mint: rgba(46,204,113,.20);
}

body{
    background: var(--bg);
}

.admin-shell{
    min-height: 80vh;
}

/* HEADER */
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:20px;
    flex-wrap: wrap;
}
.title{
    font-weight: 900;
    letter-spacing:-.6px;
    margin:0;
    color: var(--text);
}
.subtitle{
    margin-top:6px;
    margin-bottom:0;
    color: var(--muted);
}
.topbar-right{
    display:flex;
    gap:10px;
    flex-wrap: wrap;
}
.pill{
    padding: 10px 14px;
    border-radius: 999px;
    border:1px solid var(--border);
    background: rgba(255,255,255,.65);
    font-weight: 800;
    color: var(--text);
    font-size: 13px;
}
.pill.soft{
    background: var(--soft);
    border-color: rgba(255,107,139,.25);
    color: var(--accent);
}

/* MINI CARD */
.mini-card{
    border-radius: 22px;
    padding: 18px;
    background: var(--card);
    border:1px solid var(--border);
    box-shadow: 0 20px 50px rgba(0,0,0,.05);
    height:100%;
    transition:.25s ease;
}
.mini-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 25px 70px rgba(255,107,139,.10);
}
.mini-card.highlight{
    border-color: rgba(46,204,113,.35);
    box-shadow: 0 25px 70px rgba(46,204,113,.12);
}

.mini-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:14px;
}
.mini-icon{
    width:44px; height:44px;
    border-radius: 16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size: 16px;
    color: var(--text);
}
.bg-soft{ background: var(--soft); color: var(--accent); }
.bg-gold{ background: var(--gold); color: #d49b00; }
.bg-blue{ background: var(--blue); color: #268bbf; }
.bg-mint{ background: var(--mint); color: #1a9c56; }

.mini-label{
    font-size: 12px;
    font-weight: 900;
    letter-spacing: .4px;
    color: var(--muted);
}
.mini-value{
    font-size: 26px;
    font-weight: 900;
    color: var(--text);
}
.mini-desc{
    margin-top:6px;
    color: var(--muted);
    font-size: 13px;
}

/* PANELS */
.panel{
    border-radius: 22px;
    background: var(--card);
    border:1px solid var(--border);
    box-shadow: 0 20px 55px rgba(0,0,0,.05);
    padding: 18px;
}
.panel-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
}
.panel-title{
    font-weight: 900;
    margin:0;
    color: var(--text);
}
.chip{
    font-size:12px;
    font-weight: 900;
    padding: 7px 12px;
    border-radius:999px;
    background: rgba(255,255,255,.65);
    border:1px solid var(--border);
    color: var(--muted);
}
.panel-body{
    margin-top:14px;
}
.panel-foot{
    margin-top:14px;
    display:flex;
    align-items:center;
    gap:10px;
    font-size: 12px;
    color: var(--muted);
}
.dot{
    width:9px; height:9px;
    border-radius:999px;
    background: var(--accent);
}

.chart-box{
    padding: 14px;
    border-radius: 18px;
    background: linear-gradient(135deg, rgba(255,107,139,.06), rgba(200,162,200,.06));
    border: 1px solid rgba(255,107,139,.12);
}

/* MENU */
.menu-stack{
    display:flex;
    flex-direction:column;
    gap:12px;
}
.menu-btn{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 14px 16px;
    border-radius: 18px;
    background: rgba(255,255,255,.65);
    border:1px solid var(--border);
    text-decoration:none;
    font-weight: 900;
    color: var(--text);
    transition:.22s;
}
.menu-btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 18px 45px rgba(0,0,0,.06);
}
.menu-btn.outline{
    background: transparent;
    border: 2px solid rgba(255,107,139,.25);
    color: var(--accent);
}
.menu-btn .left{
    display:flex;
    align-items:center;
    gap:10px;
}
.menu-btn .icon{
    width:38px; height:38px;
    border-radius: 14px;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* FINANCE */
.finance-box{
    border-radius: 20px;
    padding: 16px;
    background: linear-gradient(135deg, rgba(46,204,113,.12), rgba(255,107,139,.10));
    border: 1px solid rgba(46,204,113,.25);
}
.finance-title{
    font-weight: 900;
    margin-bottom:12px;
    color: var(--text);
}
.finance-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:8px;
    color: var(--text);
    font-weight: 800;
}
.finance-note{
    font-size: 12px;
    margin-top:10px;
    color: var(--muted);
}

/* TABLE */
.modern-table thead th{
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--muted);
    border-bottom: 1px solid var(--border);
}
.modern-table tbody tr{
    transition:.2s;
}
.modern-table tbody tr:hover{
    background: rgba(255,107,139,.06);
}
.user-box{
    display:flex;
    align-items:center;
    gap:12px;
}
.avatar{
    width:44px; height:44px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight: 900;
}

/* BADGES */
.badge{
    padding: 7px 12px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 12px;
}
.badge.premium{
    background: rgba(255,209,102,.25);
    color: #9a6b00;
    border:1px solid rgba(255,209,102,.35);
}
.badge.free{
    background: rgba(148,163,184,.20);
    color: rgba(30,41,59,.75);
    border:1px solid rgba(148,163,184,.35);
}
</style>


{{-- ✅ CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const labels = @json($chartLabels);
    const visitorValues = @json($chartVisitorValues);
    const loginValues = @json($chartLoginValues);

    const ctx = document.getElementById('activityChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Pengunjung",
                    data: visitorValues,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 3
                },
                {
                    label: "Login",
                    data: loginValues,
                    tension: 0.4,
                    fill: false,
                    borderWidth: 3,
                    pointRadius: 3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
});
</script>
@endsection
