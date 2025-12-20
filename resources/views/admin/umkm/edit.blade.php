@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header fw-bold">Edit Data UMKM</div>
    <div class="card-body">
        <form action="{{ route('admin.umkms.update', $umkm->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="mb-3">
                <label>Nama UMKM</label>
                <input type="text" name="nama_umkm" class="form-control" value="{{ old('nama_umkm', $umkm->nama_umkm) }}" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="Makanan Berat" {{ $umkm->kategori == 'Makanan Berat' ? 'selected' : '' }}>Makanan Berat</option>
                        <option value="Makanan Ringan" {{ $umkm->kategori == 'Makanan Ringan' ? 'selected' : '' }}>Makanan Ringan</option>
                        <option value="Minuman" {{ $umkm->kategori == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>No WhatsApp</label>
                    <input type="number" name="no_whatsapp" class="form-control" value="{{ old('no_whatsapp', $umkm->no_whatsapp) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Jam Operasional</label>
                <input type="text" name="jam_operasional" class="form-control" value="{{ old('jam_operasional', $umkm->jam_operasional) }}" required>
            </div>
            
            <div class="mb-3">
                <label>Gambar (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" name="gambar" class="form-control mb-2">
                @if($umkm->gambar)
                    <div class="alert alert-info py-2">
                        <small>Gambar saat ini: <a href="{{ asset('storage/' . $umkm->gambar) }}" target="_blank">Lihat Gambar</a></small>
                    </div>
                @endif
            </div>
            
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="is_delivery" value="1" id="del" {{ $umkm->is_delivery ? 'checked' : '' }}>
                <label class="form-check-label" for="del">Menyediakan Layanan Delivery?</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Update Data</button>
                <a href="{{ route('admin.umkms.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection