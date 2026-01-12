<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; 

class Umkm extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
    'kategori' => 'array', 
    'is_delivery' => 'boolean',
];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    //Ambil Rata-rata Rating
    public function getRataRataRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1);
    }

    // Cek Status Buka/Tutup Otomatis
    public function getStatusBukaAttribute()
    {
        $jamOperasional = $this->jam_operasional;

        if (!$jamOperasional || $jamOperasional == '-' || $jamOperasional == 'Tutup Sementara') {
            return 'Tutup';
        }

        try {
            $days = [
                'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 
                'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
            ];
            
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
