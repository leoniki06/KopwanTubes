<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Membership;

class MembershipController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey     = config('services.midtrans.server_key');
        Config::$isProduction  = config('services.midtrans.is_production');
        Config::$isSanitized   = config('services.midtrans.is_sanitized');
        Config::$is3ds         = config('services.midtrans.is_3ds');
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
                'price' => 30000,
                'period' => '1 bulan',
                'features' => [
                    'Unlimited rekomendasi per hari',
                    'Akses resep eksklusif premium',
                    'History rekomendasi tanpa batas',
                    'Favorit lebih banyak',
                    'Tanpa iklan'
                ],
                'best_value' => false,
                'badge' => null
            ],
            'yearly' => [
                'name' => 'Tahunan',
                'price' => 300000,
                'period' => '1 tahun',
                'features' => [
                    'Semua fitur bulanan',
                    'Lebih hemat 2 bulan',
                    'Akses premium selama 1 tahun',
                    'Priority support',
                    'Akses fitur baru lebih dulu'
                ],
                'best_value' => true,
                'badge' => 'TERPOPULER'
            ],
            'lifetime' => [
                'name' => 'Seumur Hidup',
                'price' => 999000,
                'period' => 'Seumur hidup',
                'features' => [
                    'Semua fitur tahunan',
                    'Akses premium selamanya',
                    'Priority support selamanya',
                    'Akses fitur baru lebih dulu',
                    'Badge user eksklusif'
                ],
                'best_value' => false,
                'badge' => 'BEST DEAL'
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

        // ✅ Kalau masih premium aktif (kecuali lifetime), gak boleh beli lagi
        if ($user->isPremiumActive() && $request->type !== 'lifetime') {
            return back()->with('error', 'Anda masih memiliki membership premium aktif!');
        }

        $prices = [
            'monthly' => 30000,
            'yearly' => 300000,
            'lifetime' => 999000
        ];

        $price   = $prices[$request->type];
        $orderId = 'MB-' . time() . '-' . $user->id;

        // =================================================
        // 🔥 MODE SIMULASI (TANPA MIDTRANS)
        // =================================================
        // Kalau kamu mau langsung sukses tanpa daftar Midtrans:
        // set di .env: PAYMENT_MODE=mock
        if (env('PAYMENT_MODE', 'mock') === 'mock') {

            $membership = Membership::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'price' => $price,
                'status' => 'active',
                'payment_status' => 'paid',
                'order_id' => 'MOCK-' . time() . '-' . $user->id,
                'features' => $this->getFeatures($request->type),
                'start_date' => now(),
                'end_date' => $this->calculateEndDate($request->type),
            ]);

            // Update user jadi premium
            $user->update([
                'is_premium' => true,
                'premium_type' => $request->type,
                'premium_until' => $membership->end_date,
            ]);

            return redirect()
                ->route('membership.success')
                ->with('success', 'Upgrade Premium berhasil (mode simulasi)');
        }

        // =================================================
        // ✅ MODE MIDTRANS (BENERAN)
        // =================================================

        // ✅ bikin membership pending dulu
        $membership = Membership::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'price' => $price,
            'status' => 'pending',
            'payment_status' => 'pending',
            'order_id' => $orderId,
            'features' => $this->getFeatures($request->type)
        ]);

        // Midtrans params
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
            return back()->with('error', 'Terjadi kesalahan Midtrans: ' . $e->getMessage());
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
        return match ($type) {
            'monthly' => now()->addMonth(),
            'yearly' => now()->addYear(),
            'lifetime' => now()->addYears(100),
            default => now()->addMonth()
        };
    }

    private function getFeatures($type)
    {
        $features = [
            'monthly' => [
                'Unlimited rekomendasi per hari',
                'Akses resep eksklusif premium',
                'History rekomendasi tanpa batas',
                'Favorit lebih banyak',
                'Tanpa iklan'
            ],
            'yearly' => [
                'Semua fitur bulanan',
                'Lebih hemat 2 bulan',
                'Akses premium selama 1 tahun',
                'Priority support',
                'Akses fitur baru lebih dulu'
            ],
            'lifetime' => [
                'Semua fitur tahunan',
                'Akses premium selamanya',
                'Priority support selamanya',
                'Akses fitur baru lebih dulu',
                'Badge user eksklusif'
            ]
        ];

        return $features[$type] ?? [];
    }

    public function finish(Request $request)
    {
    $user = Auth::user();

    // ini membership terakhir yang statusnya pending
    $membership = Membership::where('user_id', $user->id)
        ->where('status', 'pending')
        ->latest()
        ->first();

    if (!$membership) {
        return redirect()->route('membership.index')->with('error', 'Membership pending tidak ditemukan.');
    }

    // ✅ update membership jadi aktif
    $membership->update([
        'status' => 'active',
        'payment_status' => 'paid',
        'start_date' => now(),
        'end_date' => $this->calculateEndDate($membership->type),
    ]);

    // ✅ update user jadi premium
    $user->update([
        'is_premium' => true,
        'premium_type' => $membership->type,
        'premium_until' => $membership->end_date,
    ]);

    return redirect()->route('membership.success')->with('success', 'Upgrade Premium berhasil 🎉');
    }

}