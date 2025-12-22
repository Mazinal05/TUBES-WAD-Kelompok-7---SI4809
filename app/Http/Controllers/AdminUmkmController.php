<?php

namespace App\Http\Controllers;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUmkmController extends Controller
{
    public function index(Request $request) {
        $query = Umkm::latest();
        if ($request->search) {
            $query->where('nama_umkm', 'like', '%'.$request->search.'%');
        }
        $umkms = $query->paginate(10);
        return view('admin.umkm.index', compact('umkms'));
    }

    public function create() { return view('admin.umkm.create'); }

    public function store(Request $request) {
        $data = $request->validate([
            'nama_umkm' => 'required', 'deskripsi' => 'required',
            'no_whatsapp' => 'required', 'kategori' => 'required|array',
            'gambar' => 'image', 'alamat' => 'required', 'koordinat' => 'nullable',
            // removed separate hari/jam validation, will handle manually
        ]);

        // Process Schedule
        $jadwal = $request->input('jadwal', []);
        $bukaDays = [];
        $jamStrings = [];

        $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($daysOrder as $day) {
            if (isset($jadwal[$day]['buka'])) { 
                $bukaDays[] = $day;
                $start = $jadwal[$day]['start'] ?? '00:00';
                $end = $jadwal[$day]['end'] ?? '00:00';
                $jamStrings[] = "$day: $start - $end";
            }
        }

        $data['hari_operasional'] = implode(', ', $bukaDays);
        if (empty($data['hari_operasional'])) $data['hari_operasional'] = 'Tutup Sementara';

        // Check if all times are the same to simplify
        $uniqueTimes = [];
        foreach ($daysOrder as $day) {
            if (isset($jadwal[$day]['buka'])) {
                $start = $jadwal[$day]['start'] ?? '00:00';
                $end = $jadwal[$day]['end'] ?? '00:00';
                $uniqueTimes[] = "$start - $end";
            }
        }
        $uniqueTimes = array_unique($uniqueTimes);

        if (count($uniqueTimes) === 1 && count($bukaDays) > 0) {
            if (count($bukaDays) == 7) {
                $data['jam_operasional'] = "Setiap Hari: " . $uniqueTimes[0];
            } else {
                $data['jam_operasional'] = implode(', ', $bukaDays) . ": " . $uniqueTimes[0];
            }
        } else {
             $data['jam_operasional'] = implode("\n", $jamStrings);
        }
        if (empty($bukaDays)) $data['jam_operasional'] = '-';


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
        $data = $request->except(['jadwal', 'gambar', 'is_delivery']); // Handle special fields manually

        // Process Schedule (Same logic as store)
        $jadwal = $request->input('jadwal', []);
        $bukaDays = [];
        $jamStrings = [];
        $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($daysOrder as $day) {
            if (isset($jadwal[$day]['buka'])) {
                $bukaDays[] = $day;
                $start = $jadwal[$day]['start'] ?? '00:00';
                $end = $jadwal[$day]['end'] ?? '00:00';
                $jamStrings[] = "$day: $start - $end";
            }
        }

        $data['hari_operasional'] = implode(', ', $bukaDays);
        if (empty($data['hari_operasional'])) $data['hari_operasional'] = 'Tutup Sementara';

        $uniqueTimes = [];
        foreach ($daysOrder as $day) {
            if (isset($jadwal[$day]['buka'])) {
                $start = $jadwal[$day]['start'] ?? '00:00';
                $end = $jadwal[$day]['end'] ?? '00:00';
                $uniqueTimes[] = "$start - $end";
            }
        }
        $uniqueTimes = array_unique($uniqueTimes);

        if (count($uniqueTimes) === 1 && count($bukaDays) > 0) {
            if (count($bukaDays) == 7) {
                $data['jam_operasional'] = "Setiap Hari: " . $uniqueTimes[0];
            } else {
                $data['jam_operasional'] = implode(', ', $bukaDays) . ": " . $uniqueTimes[0];
            }
        } else {
             $data['jam_operasional'] = implode("\n", $jamStrings);
        }
        if (empty($bukaDays)) $data['jam_operasional'] = '-';

        $data['is_delivery'] = $request->has('is_delivery');
        if ($request->file('gambar')) {
            if ($umkm->gambar) Storage::disk('public')->delete($umkm->gambar);
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
