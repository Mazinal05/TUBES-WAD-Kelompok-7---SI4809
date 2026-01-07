<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UmkmController extends Controller
{
    /**
     * GET /api/umkms
     * Menampilkan daftar semua UMKM
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $umkms = Umkm::with('menus')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua UMKM berhasil diambil',
            'data' => $umkms->map(function ($umkm) {
                return [
                    'id' => $umkm->id,
                    'nama_umkm' => $umkm->nama_umkm,
                    'kategori' => $umkm->kategori,
                    'alamat' => $umkm->alamat,
                    'deskripsi' => $umkm->deskripsi,
                    'hari_operasional' => $umkm->hari_operasional,
                    'jam_operasional' => $umkm->jam_operasional,
                    'no_whatsapp' => $umkm->no_whatsapp,
                    'is_delivery' => $umkm->is_delivery,
                    'koordinat' => $umkm->koordinat,
                    'gambar' => $umkm->gambar ? asset('storage/' . $umkm->gambar) : null,
                    'status_buka' => $umkm->status_buka,
                    'rata_rata_rating' => $umkm->rata_rata_rating,
                    'jumlah_menu' => $umkm->menus->count(),
                    'created_at' => $umkm->created_at,
                    'updated_at' => $umkm->updated_at,
                ];
            }),
            'total' => $umkms->count()
        ], 200);
    }

    /**
     * POST /api/umkms
     * Menambahkan UMKM baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_umkm' => 'required|string|max:255',
            'kategori' => 'required|array',
            'kategori.*' => 'string',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'jam_operasional' => 'nullable|string',
            'hari_operasional' => 'nullable|string',
            'no_whatsapp' => 'required|string|max:20',
            'is_delivery' => 'boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'koordinat' => 'nullable|string',
        ], [
            'nama_umkm.required' => 'Nama UMKM wajib diisi',
            'kategori.required' => 'Kategori UMKM wajib diisi',
            'kategori.array' => 'Kategori harus berupa array',
            'alamat.required' => 'Alamat UMKM wajib diisi',
            'deskripsi.required' => 'Deskripsi UMKM wajib diisi',
            'no_whatsapp.required' => 'Nomor WhatsApp wajib diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Proses upload gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('umkm', 'public');
        }

        // Buat UMKM baru
        $umkm = Umkm::create([
            'nama_umkm' => $request->nama_umkm,
            'kategori' => $request->kategori,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'hari_operasional' => $request->hari_operasional ?? '-',
            'jam_operasional' => $request->jam_operasional ?? '-',
            'no_whatsapp' => $request->no_whatsapp,
            'is_delivery' => $request->is_delivery ?? false,
            'gambar' => $gambarPath,
            'koordinat' => $request->koordinat,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'UMKM berhasil ditambahkan',
            'data' => [
                'id' => $umkm->id,
                'nama_umkm' => $umkm->nama_umkm,
                'kategori' => $umkm->kategori,
                'alamat' => $umkm->alamat,
                'deskripsi' => $umkm->deskripsi,
                'hari_operasional' => $umkm->hari_operasional,
                'jam_operasional' => $umkm->jam_operasional,
                'no_whatsapp' => $umkm->no_whatsapp,
                'is_delivery' => $umkm->is_delivery,
                'gambar' => $umkm->gambar ? asset('storage/' . $umkm->gambar) : null,
                'created_at' => $umkm->created_at,
            ]
        ], 201);
    }

    /**
     * DELETE /api/umkms/{id}
     * Menghapus UMKM berdasarkan ID
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Cari UMKM berdasarkan ID
        $umkm = Umkm::find($id);

        if (!$umkm) {
            return response()->json([
                'success' => false,
                'message' => 'UMKM tidak ditemukan'
            ], 404);
        }

        // Simpan nama untuk response
        $namaUmkm = $umkm->nama_umkm;

        // Hapus gambar jika ada
        if ($umkm->gambar && Storage::disk('public')->exists($umkm->gambar)) {
            Storage::disk('public')->delete($umkm->gambar);
        }

        // Hapus UMKM (menu terkait akan dihapus jika ada cascade)
        $umkm->delete();

        return response()->json([
            'success' => true,
            'message' => "UMKM '{$namaUmkm}' berhasil dihapus"
        ], 200);
    }
}
