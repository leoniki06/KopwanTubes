<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'price',
        'status',
        'payment_status',
        'transaction_id',
        'order_id',
        'start_date',
        'end_date',
        'features'
    ];

    protected $casts = [
        'features' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cek apakah membership masih aktif
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->payment_status === 'paid' &&
               $this->end_date > now();
    }

    // Get membership type label
    public function getTypeLabelAttribute()
    {
        $labels = [
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            'lifetime' => 'Seumur Hidup'
        ];

        return $labels[$this->type] ?? $this->type;
    }

    // Get membership features
    public function getFeaturesListAttribute()
    {
        $defaultFeatures = [
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

        return $this->features ?? $defaultFeatures[$this->type] ?? [];
    }
}