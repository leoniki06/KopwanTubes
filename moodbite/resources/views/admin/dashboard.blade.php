@extends('layouts.app')

@section('title', 'Admin Dashboard - MoodBite')

@section('content')
@php
    $latestUsers = isset($users) ? $users->take(10) : collect();

    $premiumUsers = $totalPremium ?? 0;
    $nonPremiumUsers = ($totalUsers ?? 0) - $premiumUsers;

    $chartLabels = isset($labels) ? $labels : [];
    $chartVisitorValues = isset($visitorValues) ? $visitorValues : [];
    $chartLoginValues = isset($loginValues) ? $loginValues : [];
@endphp

<div class="admin-wrap py-4">

    <div class="admin-header rounded-4 p-4 mb-4 shadow-sm">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-white">
                    <i class="fas fa-shield-halved me-2"></i> Admin Dashboard
                </h2>
                <p class="mb-0 text-white-50">
                    Kelola user, membership, dan aktivitas MoodBite di satu tempat.
                </p>
            </div>

            <div class="admin-badge">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-crown me-1"></i> ADMIN MODE
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card stat-card stat-pink border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="icon-box bg-pink-soft">
                            <i class="fas fa-users text-pink"></i>
                        </div>
                        <span class="badge bg-pink-soft text-pink fw-semibold">TOTAL</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $totalUsers ?? 0 }}</h3>
                    <p class="text-muted mb-0">Jumlah akun terdaftar</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card stat-gold border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="icon-box bg-gold-soft">
                            <i class="fas fa-crown text-gold"></i>
                        </div>
                        <span class="badge bg-gold-soft text-gold fw-semibold">PREMIUM</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $premiumUsers }}</h3>
                    <p class="text-muted mb-0">User yang masih aktif premium</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card stat-blue border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="icon-box bg-blue-soft">
                            <i class="fas fa-user text-blue"></i>
                        </div>
                        <span class="badge bg-blue-soft text-blue fw-semibold">FREE</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $nonPremiumUsers }}</h3>
                    <p class="text-muted mb-0">User tanpa premium</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-pink">
                            <i class="fas fa-chart-line me-2"></i> Grafik Aktivitas
                        </h5>
                        <span class="badge bg-pink-soft text-pink">Last 7 days</span>
                    </div>

                    <div class="chart-box rounded-4 p-4">
                        <canvas id="visitorChart" height="130"></canvas>
                    </div>

                    <p class="text-muted small mt-3 mb-0">
                        Data dihitung berdasarkan jumlah akses halaman per hari & total login per hari.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-pink">
                        <i class="fas fa-bolt me-2"></i> Quick Actions
                    </h5>

                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-admin-action">
                            <i class="fas fa-users me-2"></i> Lihat Semua User
                        </a>

                        <a href="{{ route('membership.index') }}" class="btn btn-admin-action">
                            <i class="fas fa-crown me-2"></i> Data Membership Premium
                        </a>

                        <a href="{{ route('premium.recipes.index') }}" class="btn btn-admin-action">
                            <i class="fas fa-utensils me-2"></i> Kelola Resep Premium
                        </a>

                        <a href="{{ route('dashboard') }}" class="btn btn-admin-action-outline">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-pink mb-0">
                            <i class="fas fa-user-clock me-2"></i> User Terbaru
                        </h5>
                        <span class="badge bg-pink-soft text-pink">Update realtime</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status Premium</th>
                                    <th>Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestUsers as $u)
                                    <tr>
                                        <td class="fw-semibold">{{ $u->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-mini">
                                                    {{ strtoupper(substr($u->name,0,1)) }}
                                                </div>
                                                <span class="fw-semibold">{{ $u->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $u->email }}</td>
                                        <td>
                                            <span class="badge {{ ($u->role ?? 'user') === 'admin' ? 'bg-warning text-dark' : 'bg-light text-dark' }}">
                                                {{ strtoupper($u->role ?? 'user') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($u->premium_until && $u->premium_until >= now())
                                                <span class="badge bg-success">
                                                    <i class="fas fa-crown me-1"></i> Premium
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Free</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">
                                            {{ optional($u->created_at)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada data user terbaru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<style>
:root{
    --pink:#FF6B8B;
    --pink-soft:#FFF0F5;
    --gold:#FFD166;
    --gold-soft:#FFF7DC;
    --blue:#87CEEB;
    --blue-soft:#EAF7FF;
    --text:#444;
}

.admin-wrap{ min-height: 80vh; }
.admin-header{ background: linear-gradient(135deg, #FF6B8B, #C8A2C8); }

.stat-card{ transition: .25s ease; }
.stat-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(255, 107, 139, 0.15);
}

.icon-box{
    width: 48px; height: 48px;
    display: flex; align-items:center; justify-content:center;
    border-radius: 14px; font-size: 1.2rem;
}

.text-pink{ color: var(--pink) !important; }
.bg-pink-soft{ background: var(--pink-soft) !important; }

.text-gold{ color: var(--gold) !important; }
.bg-gold-soft{ background: var(--gold-soft) !important; }

.text-blue{ color: var(--blue) !important; }
.bg-blue-soft{ background: var(--blue-soft) !important; }

.chart-box{
    background: linear-gradient(135deg, rgba(255,107,139,.08), rgba(200,162,200,.07));
}

.btn-admin-action{
    background: linear-gradient(135deg, var(--pink), var(--gold));
    color: #fff;
    border: none;
    padding: 12px 18px;
    font-weight: 700;
    border-radius: 14px;
    transition: .25s ease;
}
.btn-admin-action:hover{
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(255,107,139,.2);
    color: #fff;
}

.btn-admin-action-outline{
    border: 2px solid var(--pink);
    color: var(--pink);
    background: transparent;
    padding: 12px 18px;
    font-weight: 700;
    border-radius: 14px;
    transition: .25s ease;
}
.btn-admin-action-outline:hover{
    background: var(--pink);
    color: #fff;
}

.avatar-mini{
    width: 36px; height: 36px;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--pink), var(--gold));
    color: #fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight: 800;
}

.table thead{ border-bottom: 1px solid #eee; }
.table-hover tbody tr:hover{ background: rgba(255,107,139,0.05); }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('visitorChart');

    const labels = @json($chartLabels);
    const visitorValues = @json($chartVisitorValues);
    const loginValues = @json($chartLoginValues);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Pengunjung",
                    data: visitorValues,
                    tension: 0.35,
                    fill: true
                },
                {
                    label: "Login",
                    data: loginValues,
                    tension: 0.35,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endsection
