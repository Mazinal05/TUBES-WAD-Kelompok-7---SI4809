@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold"></i>UMKM Favorit Saya</h2>
        <p class="text-secondary">Daftar UMKM yang telah Anda simpan.</p>
    </div>
</div>

<div class="row g-4">
    @forelse($favorites as $umkm)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden menu-card">
                <div class="position-relative" style="height: 200px;">
                    @if($umkm->gambar)
                        <img src="{{ asset('storage/'.$umkm->gambar) }}" class="w-100 h-100 object-fit-cover">
                    @else
                        <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                            <i class="bi bi-shop fs-1"></i>
                        </div>
                    @endif
                    
                    <div class="position-absolute top-0 end-0 m-3">
                         @if($umkm->status_buka == 'Buka')
                            <span class="badge bg-success shadow-sm">Buka</span>
                        @else
                            <span class="badge bg-danger shadow-sm">Tutup</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    <h5 class="fw-bold mb-1 text-truncate">{{ $umkm->nama_umkm }}</h5>
                    <div class="text-warning small mb-2">
                        <i class="bi bi-star-fill"></i> {{ $umkm->rata_rata_rating }}
                    </div>
                    <p class="text-muted small mb-3 text-truncate">{{ Str::limit($umkm->deskripsi, 80) }}</p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('umkm.show', $umkm->id) }}" class="btn btn-outline-primary rounded-pill">Lihat Menu</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="bi bi-heart-break fs-1 text-muted opacity-50"></i>
            </div>
            <h5 class="text-muted">Belum ada UMKM favorit.</h5>
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill mt-3 px-4">Cari Makan Sekarang</a>
        </div>
    @endforelse
</div>
@endsection
