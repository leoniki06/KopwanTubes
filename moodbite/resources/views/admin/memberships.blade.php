@extends('layouts.app')

@section('title', 'Data Membership - MoodBite')

@section('content')
@php
    use App\Models\Membership;

    $totalRevenue = Membership::where('payment_status','paid')->sum('price');

    $revenueToday = Membership::where('payment_status','paid')
        ->whereDate('created_at', now()->toDateString())
        ->sum('price');

    $revenueThisWeek = Membership::where('payment_status','paid')
        ->whereDate('created_at','>=', now()->subDays(6)->toDateString())
        ->sum('price');

    $querySearch = request('search');
    $queryStatus = request('status');
@endphp

<div class="admin-shell py-4">

    <div class="topbar mb-4">
        <div>
            <h2 class="title">Membership Management</h2>
            <p class="subtitle">Kelola semua transaksi premium, status aktif, dan pendapatan.</p>
        </div>

        <div class="topbar-right">
            <a href="{{ route('admin.dashboard') }}" class="pill soft">
                <i class="fas fa-arrow-left me-2"></i> Dashboard Admin
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="mini-card">
                <div class="mini-top">
                    <span class="mini-icon bg-soft"><i class="fas fa-receipt"></i></span>
                    <span class="mini-label">TOTAL</span>
                </div>
                <div class="mini-value">{{ $totalMemberships ?? 0 }}</div>
                <div class="mini-desc">Semua membership tercatat</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card">
                <div class="mini-top">
                    <span class="mini-icon bg-mint"><i class="fas fa-check-circle"></i></span>
                    <span class="mini-label">ACTIVE</span>
                </div>
                <div class="mini-value">{{ $activeMemberships ?? 0 }}</div>
                <div class="mini-desc">Membership masih berjalan</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card">
                <div class="mini-top">
                    <span class="mini-icon bg-gray"><i class="fas fa-hourglass-end"></i></span>
                    <span class="mini-label">EXPIRED</span>
                </div>
                <div class="mini-value">{{ $expiredMemberships ?? 0 }}</div>
                <div class="mini-desc">Membership sudah habis</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card highlight">
                <div class="mini-top">
                    <span class="mini-icon bg-gold"><i class="fas fa-wallet"></i></span>
                    <span class="mini-label">TOTAL REVENUE</span>
                </div>
                <div class="mini-value">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
                <div class="mini-desc">Pendapatan paid</div>
            </div>
        </div>
    </div>

    <div class="panel mb-4">
        <div class="panel-head">
            <h5 class="panel-title">
                <i class="fas fa-coins me-2"></i> Revenue Snapshot
            </h5>
            <span class="chip">Paid Only</span>
        </div>

        <div class="panel-body">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="revenue-box">
                        <div class="revenue-label">Hari ini</div>
                        <div class="revenue-value">
                            Rp {{ number_format($revenueToday,0,',','.') }}
                        </div>
                        <div class="revenue-note">Total pemasukan hari ini</div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="revenue-box">
                        <div class="revenue-label">Minggu ini</div>
                        <div class="revenue-value">
                            Rp {{ number_format($revenueThisWeek,0,',','.') }}
                        </div>
                        <div class="revenue-note">7 hari terakhir</div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-4">
                    <div class="revenue-box premium">
                        <div class="revenue-label">Total keseluruhan</div>
                        <div class="revenue-value">
                            Rp {{ number_format($totalRevenue,0,',','.') }}
                        </div>
                        <div class="revenue-note">Sejak awal membership berjalan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h5 class="panel-title">
                    <i class="fas fa-users me-2"></i> Data Membership User
                </h5>
                <span class="text-muted small">Update realtime</span>
            </div>

            <form method="GET" class="filter-wrap">
                <div class="filter-box">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="{{ $querySearch }}" placeholder="Cari nama / email...">
                </div>

                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ $queryStatus === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ $queryStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ $queryStatus === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>

                <button class="btn-filter" type="submit">
                    Filter
                </button>

                <a href="{{ route('admin.memberships') }}" class="btn-reset">
                    Reset
                </a>
            </form>
        </div>

        <div class="panel-body table-responsive">
            <table class="table modern-table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Mulai</th>
                        <th>Berakhir</th>
                        <th class="text-end">Harga</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($memberships as $m)
                        <tr>
                            <td class="fw-bold">#{{ $m->id }}</td>

                            <td>
                                <div class="user-box">
                                    <div class="avatar">
                                        {{ strtoupper(substr($m->user->name ?? 'U',0,1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $m->user->name ?? '-' }}</div>
                                        <div class="small text-muted">User ID: {{ $m->user_id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-muted">{{ $m->user->email ?? '-' }}</td>

                            <td>
                                <span class="badge-plan">
                                    {{ strtoupper($m->type ?? '-') }}
                                </span>
                            </td>

                            <td>
                                @if($m->payment_status === 'paid')
                                    <span class="badge badge-paid">
                                        <i class="fas fa-check me-1"></i> PAID
                                    </span>
                                @elseif($m->payment_status === 'pending')
                                    <span class="badge badge-pending">
                                        <i class="fas fa-clock me-1"></i> PENDING
                                    </span>
                                @else
                                    <span class="badge badge-failed">
                                        {{ strtoupper($m->payment_status ?? 'UNKNOWN') }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-muted small">
                                {{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d M Y') : '-' }}
                            </td>

                            <td class="text-muted small">
                                {{ $m->end_date ? \Carbon\Carbon::parse($m->end_date)->format('d M Y') : '-' }}
                            </td>

                            <td class="fw-bold text-end">
                                Rp {{ number_format($m->price,0,',','.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Belum ada data membership.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Showing {{ $memberships->firstItem() ?? 0 }} to {{ $memberships->lastItem() ?? 0 }}
                    of {{ $memberships->total() ?? 0 }} results
                </div>
                <div>
                    {{ $memberships->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

</div>

<style>
:root{
    --bg:#f8f8f8;
    --card:#fff;
    --border:rgba(17,24,39,.08);
    --text:#111827;
    --muted:#6b7280;

    --accent:#ff6b8b;
    --accent-soft:rgba(255,107,139,.10);

    --gold:rgba(255,215,102,.20);
    --mint:rgba(34,197,94,.18);
    --gray:rgba(148,163,184,.25);
}

body{ background:var(--bg); }

.admin-shell{ min-height:80vh; }

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    flex-wrap:wrap;
    gap:16px;
}
.title{
    font-weight:900;
    margin:0;
    letter-spacing:-.6px;
    color:var(--text);
}
.subtitle{
    margin-top:8px;
    margin-bottom:0;
    color:var(--muted);
}

.pill{
    padding:10px 14px;
    border-radius:999px;
    border:1px solid var(--border);
    background:#fff;
    font-weight:800;
    font-size:13px;
    text-decoration:none;
    color:var(--text);
}
.pill.soft{
    background:var(--accent-soft);
    border-color:rgba(255,107,139,.20);
    color:var(--accent);
}

/* CARDS */
.mini-card{
    border-radius:20px;
    padding:18px;
    background:var(--card);
    border:1px solid var(--border);
    box-shadow:0 18px 40px rgba(0,0,0,.04);
    transition:.25s ease;
}
.mini-card:hover{
    transform:translateY(-3px);
    box-shadow:0 20px 55px rgba(0,0,0,.06);
}
.mini-card.highlight{
    border-color:rgba(255,215,102,.40);
}

.mini-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:12px;
}
.mini-icon{
    width:44px;height:44px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:16px;
}
.bg-soft{ background:var(--accent-soft); color:var(--accent); }
.bg-gold{ background:var(--gold); color:#b07600; }
.bg-mint{ background:var(--mint); color:#15803d; }
.bg-gray{ background:var(--gray); color:#334155; }

.mini-label{
    font-size:12px;
    font-weight:900;
    color:var(--muted);
    letter-spacing:.4px;
}
.mini-value{
    font-size:26px;
    font-weight:900;
    color:var(--text);
}
.mini-desc{
    font-size:13px;
    color:var(--muted);
    margin-top:6px;
}

/* PANEL */
.panel{
    border-radius:20px;
    background:var(--card);
    border:1px solid var(--border);
    box-shadow:0 18px 45px rgba(0,0,0,.04);
    padding:18px;
}
.panel-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:14px;
    margin-bottom:16px;
}
.panel-title{
    font-weight:900;
    margin:0;
    color:var(--text);
}
.panel-body{ margin-top:8px; }

.chip{
    font-size:12px;
    font-weight:900;
    padding:7px 12px;
    border-radius:999px;
    background:#fff;
    border:1px solid var(--border);
    color:var(--muted);
}

/* REVENUE */
.revenue-box{
    border-radius:18px;
    padding:18px;
    background:#fff;
    border:1px solid var(--border);
    transition:.2s ease;
}
.revenue-box:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 45px rgba(0,0,0,.05);
}
.revenue-box.premium{
    background:linear-gradient(135deg, rgba(255,215,102,.20), rgba(255,107,139,.12));
    border:1px solid rgba(255,215,102,.25);
}
.revenue-label{
    font-size:13px;
    font-weight:900;
    color:var(--muted);
}
.revenue-value{
    font-size:24px;
    font-weight:900;
    margin-top:6px;
    color:var(--text);
}
.revenue-note{
    font-size:12px;
    color:var(--muted);
    margin-top:5px;
}

/* FILTER UI */
.filter-wrap{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
}
.filter-box{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 14px;
    border-radius:14px;
    border:1px solid var(--border);
    background:#fff;
}
.filter-box input{
    border:none;
    outline:none;
    background:transparent;
    font-weight:600;
    width:200px;
}
.filter-box i{ color:var(--muted); }

.filter-select{
    border:1px solid var(--border);
    border-radius:14px;
    padding:10px 14px;
    background:#fff;
    font-weight:700;
    color:var(--text);
}

.btn-filter{
    padding:10px 16px;
    border-radius:14px;
    border:none;
    background:var(--accent);
    color:#fff;
    font-weight:900;
    transition:.2s ease;
}
.btn-filter:hover{
    filter:brightness(.95);
    transform:translateY(-1px);
}

.btn-reset{
    padding:10px 16px;
    border-radius:14px;
    border:1px solid rgba(255,107,139,.25);
    background:var(--accent-soft);
    color:var(--accent);
    font-weight:900;
    text-decoration:none;
}

/* TABLE */
.modern-table thead th{
    position:sticky;
    top:0;
    background:#fff;
    z-index:2;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.35px;
    color:var(--muted);
    border-bottom:1px solid var(--border);
}
.modern-table tbody tr{
    transition:.2s ease;
}
.modern-table tbody tr:hover{
    background:rgba(255,107,139,.05);
}

/* USER */
.user-box{
    display:flex;
    align-items:center;
    gap:12px;
}
.avatar{
    width:44px;height:44px;
    border-radius:16px;
    background:linear-gradient(135deg, var(--accent), #ff9ab0);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
}

/* BADGES */
.badge{
    padding:7px 12px;
    border-radius:999px;
    font-weight:900;
    font-size:12px;
}
.badge-paid{
    background:rgba(34,197,94,.18);
    border:1px solid rgba(34,197,94,.30);
    color:#15803d;
}
.badge-pending{
    background:rgba(255,215,102,.25);
    border:1px solid rgba(255,215,102,.35);
    color:#9a6b00;
}
.badge-failed{
    background:rgba(148,163,184,.25);
    border:1px solid rgba(148,163,184,.35);
    color:#334155;
}

.badge-plan{
    padding:7px 12px;
    border-radius:999px;
    background:var(--accent-soft);
    border:1px solid rgba(255,107,139,.22);
    font-weight:900;
    font-size:12px;
    color:var(--accent);
}

@media(max-width:768px){
    .filter-box input{ width:160px; }
}
</style>
@endsection
