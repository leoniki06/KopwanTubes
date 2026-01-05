<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard()
    {
        // List semua user
        $users = User::orderBy('created_at', 'desc')->get();

        // Stats User
        $totalUsers = User::count();
        $totalPremium = User::where('is_premium', true)
            ->where('premium_until', '>', now())
            ->count();

        // Finance Stats
        $totalRevenue = Membership::where('payment_status', 'paid')->sum('price');

        $revenueToday = Membership::where('payment_status', 'paid')
            ->whereDate('created_at', now()->toDateString())
            ->sum('price');

        $revenueThisWeek = Membership::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->sum('price');

        // Top plan
        $topPlan = Membership::select('type', DB::raw('COUNT(*) as total'))
            ->where('payment_status', 'paid')
            ->groupBy('type')
            ->orderByDesc('total')
            ->first();

        // Chart setup
        $labels = [];
        $visitorValues = [];
        $loginValues = [];
        $revenueValues = [];

        $startDate = now()->subDays(6)->toDateString();

        $visitorData = collect();
        $loginData = collect();
        $revenueData = collect();

        // Visitor Logs
        if (Schema::hasTable('visitor_logs')) {
            $visitorData = DB::table('visitor_logs')
                ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT ip_address) as total')
                ->whereDate('created_at', '>=', $startDate)
                ->groupBy('date')
                ->get()
                ->keyBy('date');
        }

        // Login Logs
        if (Schema::hasTable('login_logs')) {
            $loginData = DB::table('login_logs')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->whereDate('created_at', '>=', $startDate)
                ->groupBy('date')
                ->get()
                ->keyBy('date');
        }

        // Revenue per day
        $revenueData = Membership::selectRaw('DATE(created_at) as date, SUM(price) as total')
            ->where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy('date')
            ->get()
            ->keyBy('date');
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $labels[] = $date;
            $visitorValues[] = optional($visitorData->get($date))->total ?? 0;
            $loginValues[] = optional($loginData->get($date))->total ?? 0;
            $revenueValues[] = optional($revenueData->get($date))->total ?? 0;
        }

        return view('admin.dashboard', compact(
            'users',
            'totalUsers',
            'totalPremium',
            'labels',
            'visitorValues',
            'loginValues',
            'totalRevenue',
            'revenueToday',
            'revenueThisWeek',
            'revenueValues',
            'topPlan'
        ));
    }

    public function memberships()
    {
        $memberships = Membership::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalMemberships = Membership::count();

        $activeMemberships = Membership::where('status', 'active')
            ->where('end_date', '>', now())
            ->count();

        $expiredMemberships = Membership::where('end_date', '<=', now())->count();

        return view('admin.memberships', compact(
            'memberships',
            'totalMemberships',
            'activeMemberships',
            'expiredMemberships'
        ));
    }
}