@extends('layouts.app')

@section('content')

<div class="card mb-4 bg-white border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('home') }}" method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Mau makan apa hari ini? Cari nama UMKM..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="Makanan Berat" {{ request('kategori') == 'Makanan Berat' ? 'selected' : '' }}>Makanan Berat</option>
                        <option value="Makanan Ringan" {{ request('kategori') == 'Makanan Ringan' ? 'selected' : '' }}>Makanan Ringan</option>
                        <option value="Minuman" {{ request('kategori') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <div class="form-check pt-2">
                        <input class="form-check-input" type="checkbox" name="delivery" value="1" id="delCheck" {{ request('delivery') ? 'checked' : '' }}>
                        <label class="form-check-label small fw-bold" for="delCheck">Bisa Delivery</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<h4 class="mb-4 fw-bold text-dark">Daftar Kuliner Pilihan</h4>

<div class="row">
    @forelse($umkms as $umkm)
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm hover-shadow transition">
            
            <div class="position-absolute top-0 start-0 m-2">
                @if($umkm->status_buka == 'Buka')
                    <span class="badge bg-success shadow-sm">BUKA</span>
                @elseif($umkm->status_buka == 'Tutup')
                    <span class="badge bg-danger shadow-sm">TUTUP</span>
                @endif
            </div>

            @if($umkm->gambar)
                <img src="{{ asset('storage/'.$umkm->gambar) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $umkm->nama_umkm }}">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                    <i class="bi bi-image fs-1"></i>
                </div>
            @endif

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title fw-bold mb-0 text-truncate">{{ $umkm->nama_umkm }}</h5>
                    
                    <div class="d-flex align-items-center text-warning small bg-light px-2 py-1 rounded">
                        <i class="bi bi-star-fill me-1"></i> 
                        <span class="fw-bold text-dark">{{ $umkm->rata_rata_rating }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="badge bg-secondary fw-normal">
                        {{ is_array($umkm->kategori) ? implode(', ', $umkm->kategori) : $umkm->kategori }}
                    </span>
                    @if($umkm->is_delivery)
                        <span class="badge bg-primary fw-normal"><i class="bi bi-truck"></i> Delivery</span>
                    @endif
                </div>

                <p class="card-text text-muted small">
                    {{ Str::limit($umkm->deskripsi, 80, '...') }}
                </p>
                
                <hr class="my-3">

                <a href="{{ route('umkm.show', $umkm->id) }}" class="btn btn-outline-primary w-100">
                    Lihat Menu & Pesan
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 py-5 text-center">
        <div class="mb-3">
            <i class="bi bi-search fs-1 text-muted"></i>
        </div>
        <h5 class="text-muted">Yah, Kuliner tidak ditemukan.</h5>
        <p class="text-secondary small">Coba cari dengan kata kunci lain atau ubah filter.</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-sm mt-2">Reset Pencarian</a>
    </div>
    @endforelse
</div>
@endsection