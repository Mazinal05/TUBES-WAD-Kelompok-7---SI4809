<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Membuat Akun Admin Otomatis
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@makanapa.com',
            'password' => Hash::make('password123'), // Password admin
            'role' => 'admin', // KUNCI UTAMA: set role ke admin
        ]);
        
        // Opsional: Buat user biasa untuk tes
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pengguna',
        ]);
    }
}
