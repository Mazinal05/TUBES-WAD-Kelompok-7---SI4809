@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<div class="row justify-content-center mb-5 mt-3">
    <div class="col-lg-10 text-center">
        <h1 class="fw-bold display-4 text-dark mb-3" style="font-family: 'Fredoka', sans-serif;">
            Mau <span class="text-primary">MakanApa?</span> Hari Ini?
        </h1>
        <p class="text-secondary mb-4 fs-5">Temukan kuliner favoritmu di sekitar Telkom University.</p>
        
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden p-1 bg-white search-card">
            <div class="card-body p-1">
                <form action="{{ route('home') }}" method="GET" id="searchForm">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-6 search-divider">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 ps-4"><i class="bi bi-search text-secondary"></i></span>
                                <input type="text" name="search" class="form-control border-0 bg-transparent py-3" placeholder="Cari nama UMKM..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4 search-divider">
                            <select name="kategori" class="form-select border-0 py-3 text-secondary" style="cursor: pointer;">
                                <option value="">Semua Kategori</option>
                                <option value="Makanan Berat" {{ request('kategori') == 'Makanan Berat' ? 'selected' : '' }}>Makanan Berat</option>
                                <option value="Makanan Ringan" {{ request('kategori') == 'Makanan Ringan' ? 'selected' : '' }}>Makanan Ringan</option>
                                <option value="Minuman" {{ request('kategori') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            </select>
                        </div>
                        <div class="col-md-2 p-1">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold py-3 h-100">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            <style>
                .btn-check:checked + .btn-outline-danger {
                    background-color: var(--brand-red);
                    color: white;
                    box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
                    border-color: var(--brand-red);
                }
                .btn-outline-danger {
                    color: var(--brand-dark);
                    border-color: #e0e0e0;
                    background: white;
                }
                .btn-outline-danger:hover {
                    background-color: #fff5f5;
                    border-color: var(--brand-red);
                    color: var(--brand-red);
                }
            </style>
            <input type="checkbox" class="btn-check" name="delivery" id="delCheck" value="1" autocomplete="off" {{ request('delivery') ? 'checked' : '' }} form="searchForm">
            <label class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2" for="delCheck">
                <i class="bi bi-truck fs-5"></i> 
                <span>Bisa Delivery</span>
                @if(request('delivery'))
                    <i class="bi bi-check-circle-fill ms-1"></i>
                @endif
            </label>
        </div>
    </div>
</div>

<!-- CONTENT SECTION -->
<div class="d-flex justify-content-between align-items-center mb-4 pt-4 border-top">
    <h4 class="fw-bold text-dark m-0"><i class="bi bi-shop-window me-2 text-primary"></i>Kuliner Pilihan</h4>
    <span class="badge bg-light text-secondary rounded-pill px-3 py-2 border">Total: {{ $umkms->count() }} UMKM</span>
</div>

<div class="row g-4">
    @forelse($umkms as $umkm)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative group hover-shadow-lg transition-all">
            
            <!-- Link Cover -->
            <a href="{{ route('umkm.show', $umkm->id) }}" class="stretched-link"></a>

            <!-- Image Area -->
            <div class="position-relative overflow-hidden" style="height: 220px;">
                @if($umkm->gambar)
                    <img src="{{ asset('storage/'.$umkm->gambar) }}" class="w-100 h-100 object-fit-cover transition-transform" alt="{{ $umkm->nama_umkm }}">
                @else
                    <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                        <i class="bi bi-image fs-1 opacity-25"></i>
                    </div>
                @endif
                
                <!-- Status Badge (Floating) -->
                <div class="position-absolute top-0 end-0 m-3 z-10">
                    @if($umkm->status_buka == 'Buka')
                        <span class="badge bg-success bg-gradient shadow-sm border border-white px-3 py-2 rounded-pill">
                            <i class="bi bi-clock me-1"></i> BUKA
                        </span>
                    @else
                        <span class="badge bg-danger bg-gradient shadow-sm border border-white px-3 py-2 rounded-pill">
                            <i class="bi bi-slash-circle me-1"></i> TUTUP
                        </span>
                    @endif
                </div>

                <!-- Gradient Overlay -->
                <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);">
                    <!-- Category Badge -->
                     <span class="badge bg-white text-dark shadow-sm fw-normal">
                        {{ is_array($umkm->kategori) ? implode(', ', $umkm->kategori) : $umkm->kategori }}
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4 bg-white">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title fw-bold text-dark mb-0 text-truncate pe-2">{{ $umkm->nama_umkm }}</h5>
                    <div class="d-flex align-items-center text-warning small bg-light px-2 py-1 rounded-3">
                        <i class="bi bi-star-fill me-1"></i> 
                        <span class="fw-bold text-dark">{{ $umkm->rata_rata_rating }}</span>
                    </div>
                </div>

                <p class="card-text text-secondary small mb-3 line-clamp-2" style="min-height: 40px;">
                    {{ Str::limit($umkm->deskripsi, 90) }}
                </p>

                <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light">
                     @if($umkm->is_delivery)
                        <small class="text-primary fw-bold"><i class="bi bi-truck me-1"></i> Delivery Available</small>
                    @else
                        <small class="text-muted"><i class="bi bi-shop me-1"></i> Dine-in Only</small>
                    @endif
                    
                    <span class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-primary group-hover-btn">
                        Lihat Menu <i class="bi bi-arrow-right ms-1"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="mb-4">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                <i class="bi bi-search fs-1 text-secondary opacity-50"></i>
            </div>
        </div>
        <h4 class="fw-bold text-dark">Yah, Kuliner tidak ditemukan!</h4>
        <p class="text-secondary">Coba gunakan kata kunci lain atau reset filter pencarianmu.</p>
        <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4 mt-2">Reset Pencarian</a>
    </div>
    @endforelse
</div>

<style>
    /* Custom Utilities for Home */
    /* Responsive Search Bar */
    @media (min-width: 768px) {
        .search-divider { border-right: 1px solid #dee2e6; }
        .search-card { border-radius: 50rem !important; } /* Pill on desktop */
    }
    @media (max-width: 767px) {
        .search-divider { border-bottom: 1px solid #f0f0f0; }
        .display-4 { font-size: 2.5rem; } /* Smaller title on mobile */
    }

    .hover-shadow-lg:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important; z-index: 10; }
    .transition-all { transition: all 0.3s ease; }
    .transition-transform { transition: transform 0.3s ease; }
    .group:hover .transition-transform { transform: scale(1.05); }
    .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .object-fit-cover { object-fit: cover; }
    .z-10 { z-index: 10; }
    
    /* Make the button turn red on hover of the whole card */
    .group:hover .group-hover-btn { background-color: var(--brand-red); color: white !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reset all carts when visiting Home Page (New Journey)
        Object.keys(localStorage).forEach(function(key) {
            if (key.startsWith('makanapa_cart_')) {
                localStorage.removeItem(key);
            }
        });
    });
</script>
@endsection