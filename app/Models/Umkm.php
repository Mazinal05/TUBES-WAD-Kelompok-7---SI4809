<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Pastikan import ini ada

class Umkm extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
    'kategori' => 'array', 
    'is_delivery' => 'boolean',
];

    // Relasi ke Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    // FITUR 2: Ambil Rata-rata Rating
    // Cara panggil: $umkm->rata_rata_rating
    public function getRataRataRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1);
    }

    // FITUR 1: Cek Status Buka/Tutup Otomatis
    // Cara panggil: $umkm->status_buka
    public function getStatusBukaAttribute()
    {
        $jamOperasional = $this->jam_operasional;

        if (!$jamOperasional || $jamOperasional == '-' || $jamOperasional == 'Tutup Sementara') {
            return 'Tutup';
        }

        try {
            // Mapping hari Inggris ke Indonesia
            $days = [
                'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 
                'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
            ];
            
            // GUNAKAN TIMEZONE ASIA/JAKARTA (WIB)
            $now = Carbon::now('Asia/Jakarta');
            $today = $days[$now->format('l')];

            // 1. Cek format "Setiap Hari: 08:00 - 22:00"
            if (str_contains($jamOperasional, 'Setiap Hari')) {
                preg_match('/(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})/', $jamOperasional, $matches);
                if (count($matches) == 3) {
                    $buka = Carbon::createFromFormat('H:i', $matches[1], 'Asia/Jakarta');
                    $buka->setDate($now->year, $now->month, $now->day);
                    
                    $tutup = Carbon::createFromFormat('H:i', $matches[2], 'Asia/Jakarta');
                    $tutup->setDate($now->year, $now->month, $now->day);

                    return $now->between($buka, $tutup) ? 'Buka' : 'Tutup';
                }
            }

            // 2. Cek format per baris "Senin: 08:00 - 17:00"
            // Pecah berdasarkan baris atau koma
            $lines = preg_split("/\r\n|\n|\r/", $jamOperasional);
            
            foreach ($lines as $line) {
                if (str_contains($line, $today)) {
                    preg_match('/(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})/', $line, $matches);
                    if (count($matches) == 3) {
                        $buka = Carbon::createFromFormat('H:i', $matches[1], 'Asia/Jakarta');
                        $buka->setDate($now->year, $now->month, $now->day);

                        $tutup = Carbon::createFromFormat('H:i', $matches[2], 'Asia/Jakarta');
                        $tutup->setDate($now->year, $now->month, $now->day);
                        
                        return $now->between($buka, $tutup) ? 'Buka' : 'Tutup'; 
                    }
                }
            }
            
            // 3. Fallback: Jika string hanya berisi jam "08:00 - 22:00" tanpa hari
            $hasDayName = false;
            foreach($days as $day) {
                if(str_contains($jamOperasional, $day)) {
                    $hasDayName = true;
                    break;
                }
            }

            if (!$hasDayName) {
                preg_match('/(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})/', $jamOperasional, $matches);
                if (count($matches) == 3) {
                    $buka = Carbon::createFromFormat('H:i', $matches[1], 'Asia/Jakarta');
                    $buka->setDate($now->year, $now->month, $now->day);
                    
                    $tutup = Carbon::createFromFormat('H:i', $matches[2], 'Asia/Jakarta');
                    $tutup->setDate($now->year, $now->month, $now->day);

                    return $now->between($buka, $tutup) ? 'Buka' : 'Tutup';
                }
            }

            return 'Tutup';

        } catch (\Exception $e) {
            return 'Info'; 
        }
    }
    
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', 'like', '%' . $kategori . '%');
    }

    public function scopeDelivery($query)
    {
        return $query->where('is_delivery', true);
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'umkm_id', 'user_id')->withTimestamps();
    }
}
