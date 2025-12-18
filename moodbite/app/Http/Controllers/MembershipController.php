<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Membership;
use App\Models\User;

class MembershipController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans (DISIAPKAN, BELUM DIPAKAI)
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    // =============================
    // HALAMAN PILIH MEMBERSHIP
    // =============================
    public function index()
    {
        $user = Auth::user();
        $currentMembership = $user->currentMembership();

        $plans = [
            'monthly' => [
                'name' => 'Bulanan',
                'price' => 49000,
                'period' => '1 bulan',
                'features' => [
                    'Akses rekomendasi premium',
                    'Detail restoran lengkap',
                    'Resep eksklusif',
                    'Priority support',
                    'Diskon partner 10%'
                ],
                'best_value' => false
            ],
            'yearly' => [
                'name' => 'Tahunan',
                'price' => 490000,
                'period' => '1 tahun',
                'features' => [
                    'Semua fitur bulanan',
                    'Gratis 2 bulan',
                    'Diskon partner 15%',
                    'Konsultasi nutrisi',
                    'Meal planning'
                ],
                'best_value' => true
            ],
            'lifetime' => [
                'name' => 'Seumur Hidup',
                'price' => 1999000,
                'period' => 'Seumur hidup',
                'features' => [
                    'Semua fitur tahunan',
                    'Akses seumur hidup',
                    'Diskon partner 20%',
                    'Personal concierge',
                    'Event eksklusif'
                ],
                'best_value' => false
            ]
        ];

        return view('membership.index', compact('plans', 'currentMembership', 'user'));
    }

    // =============================
    // PROSES PEMBELIAN MEMBERSHIP
    // =============================
    public function purchase(Request $request)
    {
        $request->validate([
            'type' => 'required|in:monthly,yearly,lifetime'
        ]);

        $user = Auth::user();

        // =============================
        // 🔥 MODE SIMULASI (TANPA MIDTRANS)
        // =============================
        if (env('PAYMENT_MODE') === 'mock') {

            $membership = Membership::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'price' => 0,
                'status' => 'active',
                'payment_status' => 'paid',
                'order_id' => 'MOCK-' . time() . '-' . $user->id,
                'features' => $this->getFeatures($request->type),
                'start_date' => now(),
                'end_date' => $this->calculateEndDate($request->type),
            ]);

            // Update status premium user
            $user->update([
                'is_premium' => true,
                'premium_type' => $request->type,
                'premium_until' => $membership->end_date,
            ]);

            return redirect()
                ->route('membership.success')
                ->with('success', 'Upgrade Premium berhasil (mode simulasi)');
        }

        // ==================================================
        // ⬇️ KODE MIDTRANS ASLI (BELUM DIPAKAI SEKARANG)
        // ==================================================

        // Cek jika sudah premium
        if ($user->isPremium() && $request->type !== 'lifetime') {
            return back()->with('error', 'Anda sudah memiliki membership aktif!');
        }

        $prices = [
            'monthly' => 49000,
            'yearly' => 490000,
            'lifetime' => 1999000
        ];

        $price = $prices[$request->type];
        $orderId = 'MB-' . time() . '-' . $user->id;

        $membership = Membership::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'price' => $price,
            'status' => 'pending',
            'payment_status' => 'pending',
            'order_id' => $orderId,
            'features' => $this->getFeatures($request->type)
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'membership-' . $request->type,
                    'price' => $price,
                    'quantity' => 1,
                    'name' => 'Membership MoodBite ' . ucfirst($request->type),
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('membership.payment', compact('snapToken', 'membership'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =============================
    // HALAMAN SUKSES
    // =============================
    public function success()
    {
        $user = Auth::user();
        $currentMembership = $user->currentMembership();

        return view('membership.success', compact('user', 'currentMembership'));
    }

    // =============================
    // RIWAYAT MEMBERSHIP
    // =============================
    public function history()
    {
        $user = Auth::user();
        $memberships = $user->memberships()->latest()->paginate(10);

        return view('membership.history', compact('memberships', 'user'));
    }

    // =============================
    // HELPER
    // =============================
    private function calculateEndDate($type)
    {
        switch ($type) {
            case 'monthly':
                return now()->addMonth();
            case 'yearly':
                return now()->addYear();
            case 'lifetime':
                return now()->addYears(100);
            default:
                return now()->addMonth();
        }
    }

    private function getFeatures($type)
    {
        $features = [
            'monthly' => [
                'Akses rekomendasi premium',
                'Detail restoran lengkap',
                'Resep eksklusif',
                'Priority support',
                'Diskon partner 10%'
            ],
            'yearly' => [
                'Semua fitur bulanan',
                'Gratis 2 bulan',
                'Diskon partner 15%',
                'Konsultasi nutrisi',
                'Meal planning'
            ],
            'lifetime' => [
                'Semua fitur tahunan',
                'Akses seumur hidup',
                'Diskon partner 20%',
                'Personal concierge',
                'Event eksklusif'
            ]
        ];

        return $features[$type] ?? [];
    }
}
