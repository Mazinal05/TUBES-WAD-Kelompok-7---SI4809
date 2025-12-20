<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Pastikan import ini ada

class Umkm extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi ke Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // FITUR 2: Ambil Rata-rata Rating
    // Cara panggil: $umkm->rata_rata_rating
    public function getRataRataRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1);
    }

    // FITUR 1: Cek Status Buka/Tutup Otomatis
    // Asumsi format jam di database harus konsisten: "08:00 - 22:00"
    // Cara panggil: $umkm->status_buka
    public function getStatusBukaAttribute()
    {
        // Jika format jam tidak ada tanda strip "-", anggap info teks biasa
        if (!str_contains($this->jam_operasional, '-')) {
            return 'Info'; 
        }

        try {
            // Pecah string "08:00 - 22:00"
            $jam = explode('-', $this->jam_operasional);
            $buka = Carbon::createFromFormat('H:i', trim($jam[0]));
            $tutup = Carbon::createFromFormat('H:i', trim($jam[1]));
            $sekarang = Carbon::now();

            // Cek apakah sekarang berada di antara jam buka dan tutup
            if ($sekarang->between($buka, $tutup)) {
                return 'Buka';
            } else {
                return 'Tutup';
            }
        } catch (\Exception $e) {
            return 'Info'; // Jika format jam salah, kembalikan default
        }
    }
}
