<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mood',
        'results'
    ];

    protected $casts = [
        'results' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
