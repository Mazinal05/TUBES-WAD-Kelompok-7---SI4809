<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // <--- WAJIB ADA AGAR TIDAK ERROR "Class Carbon not found"

class Umkm extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    // Relasi ke tabel Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Aksessor: Hitung Rata-rata Rating (Contoh: 4.5)
    public function getRataRataRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1);
    }

    // Aksessor: Cek Status Buka/Tutup (Logika Kuat)
    public function getStatusBukaAttribute()
    {
        // 1. Bersihkan format jam (ubah titik jadi titik dua)
        $jamRaw = str_replace('.', ':', $this->jam_operasional);
        
        // 2. Jika tidak ada tanda strip (-), anggap sebagai Info Teks
        if (!str_contains($jamRaw, '-')) {
            return 'Info'; 
        }

        try {
            $parts = explode('-', $jamRaw);
            $buka = Carbon::parse(trim($parts[0]));
            $tutup = Carbon::parse(trim($parts[1]));
            $sekarang = Carbon::now();

            // Logika lintas hari (misal buka 18:00 tutup 02:00 pagi)
            if ($tutup->lessThan($buka)) {
                $tutup->addDay();
                if ($sekarang->lessThan($buka)) {
                    $sekarang->addDay();
                }
            }

            // Cek apakah sekarang ada di antara jam buka dan tutup
            if ($sekarang->between($buka, $tutup)) {
                return 'Buka';
            } else {
                return 'Tutup';
            }
        } catch (\Exception $e) {
            return 'Info'; // Jika error parsing, kembalikan 'Info'
        }
    }

    // Scope untuk Filter Delivery
    public function scopeDelivery($query)
    {
        return $query->where('is_delivery', true);
    }

    // Scope untuk Filter Kategori
    public function scopeKategori($query, $tipe)
    {
        return $query->where('kategori', $tipe);
    }
}