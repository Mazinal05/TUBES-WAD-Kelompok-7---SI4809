<?php

namespace App\Http\Controllers;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUmkmController extends Controller
{
    public function index() {
        $umkms = Umkm::latest()->paginate(10);
        return view('admin.umkm.index', compact('umkms'));
    }

    public function create() { return view('admin.umkm.create'); }

    public function store(Request $request) {
        $data = $request->validate([
            'nama_umkm' => 'required', 'deskripsi' => 'required',
            'no_whatsapp' => 'required', 'kategori' => 'required',
            'jam_operasional' => 'required', 'gambar' => 'image'
        ]);
        $data['is_delivery'] = $request->has('is_delivery');
        if ($request->file('gambar')) $data['gambar'] = $request->file('gambar')->store('umkm', 'public');

        Umkm::create($data);
        return redirect()->route('admin.umkms.index');
    }

    public function edit($id) {
        $umkm = Umkm::findOrFail($id);
        return view('admin.umkm.edit', compact('umkm'));
    }

    public function update(Request $request, $id) {
        $umkm = Umkm::findOrFail($id);
        $data = $request->all();
        $data['is_delivery'] = $request->has('is_delivery');
        if ($request->file('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('umkm', 'public');
        }
        $umkm->update($data);
        return redirect()->route('admin.umkms.index');
    }

    public function destroy($id) {
        Umkm::destroy($id);
        return back();
    }
}
