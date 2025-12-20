<?php

namespace App\Http\Controllers;
use App\Models\Umkm;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {
        $query = Umkm::query();
        // Implementasi Filter PRD No. 8, 9, 10
        if ($request->search) $query->where('nama_umkm', 'like', '%'.$request->search.'%');
        if ($request->delivery) $query->delivery();
        if ($request->kategori) $query->kategori($request->kategori);

        $umkms = $query->get();
        return view('home', compact('umkms'));
    }

    public function show($id) {
        $umkm = Umkm::findOrFail($id);
        return view('umkm.detail', compact('umkm'));
    }
}
