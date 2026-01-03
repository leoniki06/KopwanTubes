<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1) List semua user
        $users = User::orderBy('created_at', 'desc')->get();

        // 2) Stats premium
        $totalUsers = User::count();
        $totalPremium = User::where('is_premium', true)->count();

        // 3) Visitor log (kalau tabel belum ada -> aman)
        $labels = collect([]);
        $values = collect([]);

        if (Schema::hasTable('visitor_logs')) {
            $visitorData = DB::table('visitor_logs')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->limit(7)
                ->get();

            $labels = $visitorData->pluck('date');
            $values = $visitorData->pluck('total');
        } else {
            // kalau mau dummy biar grafik tetap ada
            $labels = collect([
                now()->subDays(6)->format('Y-m-d'),
                now()->subDays(5)->format('Y-m-d'),
                now()->subDays(4)->format('Y-m-d'),
                now()->subDays(3)->format('Y-m-d'),
                now()->subDays(2)->format('Y-m-d'),
                now()->subDays(1)->format('Y-m-d'),
                now()->format('Y-m-d'),
            ]);

            $values = collect([2, 5, 3, 7, 4, 6, 8]);
        }

        return view('admin.dashboard', compact(
            'users',
            'totalUsers',
            'totalPremium',
            'labels',
            'values'
        ));
    }
}
