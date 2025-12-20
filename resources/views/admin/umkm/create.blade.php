@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Tambah UMKM Baru</div>
    <div class="card-body">
        <form action="{{ route('admin.umkms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label>Nama UMKM</label>
                <input type="text" name="nama_umkm" class="form-control" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="Makanan Berat">Makanan Berat</option>
                        <option value="Makanan Ringan">Makanan Ringan</option>
                        <option value="Minuman">Minuman</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>No WhatsApp (628xxx)</label>
                    <input type="number" name="no_whatsapp" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label>Jam Operasional</label>
                <input type="text" name="jam_operasional" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Gambar</label>
                <input type="file" name="gambar" class="form-control">
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_delivery" value="1" id="del">
                <label class="form-check-label" for="del">Bisa Delivery?</label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.umkms.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection