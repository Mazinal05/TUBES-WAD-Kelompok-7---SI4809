<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Umkm;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class UmkmController extends Controller
{

    //halaman checkout
    public function showCheckout(Request $request, $id)
    {
        $umkm = Umkm::findOrFail($id);
        $cartData = json_decode($request->cart_json, true) ?? [];
        
        if(empty($cartData)) {
            return back()->with('error', 'Keranjang Anda kosong.');
        }

        return view('umkm.checkout', compact('umkm', 'cartData'));
    }

    //proses order ke whatsapp
    public function processOrder(Request $request, $id)
    {
        $umkm = Umkm::findOrFail($id);

        $noWa = $umkm->no_whatsapp;

        $noWa = preg_replace('/[^0-9]/', '', $noWa);

        if (substr($noWa, 0, 1) == '0') {
            $noWa = '62' . substr($noWa, 1);
        }


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

        $url = "https://wa.me/$noWa?text=" . urlencode($text);


        return redirect()->away($url);
    }

    //simpan review
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        $existingReview = Review::where('user_id', Auth::id())
            ->where('umkm_id', $id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk UMKM ini.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'umkm_id' => $id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}