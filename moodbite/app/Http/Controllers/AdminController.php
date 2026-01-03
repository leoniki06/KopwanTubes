<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        $totalUsers = User::count();
        $totalPremium = User::where('is_premium', true)->count();

        $labels = [];
        $visitorValues = [];
        $loginValues = [];

        $startDate = now()->subDays(6)->toDateString();

        $visitorData = collect();
        $loginData = collect();

        if (Schema::hasTable('visitor_logs')) {
            $visitorData = DB::table('visitor_logs')
                ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT ip_address) as total')
                ->whereDate('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');
        }

        if (Schema::hasTable('login_logs')) {
            $loginData = DB::table('login_logs')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->whereDate('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');
        }

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $labels[] = $date;
            $visitorValues[] = optional($visitorData->get($date))->total ?? 0;
            $loginValues[] = optional($loginData->get($date))->total ?? 0;
        }

        return view('admin.dashboard', compact(
            'users',
            'totalUsers',
            'totalPremium',
            'labels',
            'visitorValues',
            'loginValues'
        ));
    }
}
