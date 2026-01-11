<?php

namespace App\Http\Controllers;
use App\Models\Umkm;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //halaman utama
    public function index(Request $request) {
        $query = Umkm::query();
        if ($request->search) $query->where('nama_umkm', 'like', '%'.$request->search.'%');
        if ($request->delivery) $query->delivery();
        if ($request->kategori) $query->kategori($request->kategori);

        $umkms = $query->get();
        return view('home', compact('umkms'));
    }

    //detail umkm
    public function show($id) {
        $umkm = Umkm::with(['menus', 'reviews.user'])->findOrFail($id);
        return view('umkm.detail', compact('umkm'));
    }
}
