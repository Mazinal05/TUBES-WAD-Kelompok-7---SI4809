<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Umkm;
use App\Models\Review; // Tambahkan ini
use Illuminate\Support\Facades\Auth;

class UmkmController extends Controller
{
    // ... method lain (seperti index/show jika ada) ...

    public function processOrder(Request $request, $id)
    {
        $umkm = Umkm::findOrFail($id);

        // 1. Ambil Nomor WA & Bersihkan Format
        $noWa = $umkm->no_whatsapp;
        
        // Hapus karakter selain angka
        $noWa = preg_replace('/[^0-9]/', '', $noWa);

        // Ubah 08xxx jadi 628xxx
        if (substr($noWa, 0, 1) == '0') {
            $noWa = '62' . substr($noWa, 1);
        }

        // 2. Susun Pesan
        $namaUser = Auth::user()->name;
        $pesanan = $request->pesanan;
        $alamat = $request->alamat;
        $isPickup = $request->input('is_pickup', false);

        $text = "Halo Kak, saya *$namaUser* ingin pesan di *$umkm->nama_umkm*.\n\n";
        $text .= "*Detail Pesanan:*\n$pesanan\n\n";

        if ($isPickup) {
            $text .= "*Metode:* Ambil Sendiri (Pick Up)\n";
        } else {
            $text .= "*Alamat Pengantaran:*\n$alamat\n";
        }

        $text .= "\nTerima kasih!";

        // 3. Buat URL WhatsApp
        $url = "https://wa.me/$noWa?text=" . urlencode($text);

        // 4. Redirect ke WA
        return redirect()->away($url);
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'umkm_id' => $id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}