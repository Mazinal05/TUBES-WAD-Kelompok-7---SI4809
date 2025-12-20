<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // TAMBAHKAN INI (Agar kolom ini diizinkan untuk diisi data)
    protected $fillable = [
        'user_id',
        'umkm_id',
        'rating',
        'komentar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}