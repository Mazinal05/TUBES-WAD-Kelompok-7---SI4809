@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        @if($umkm->gambar)
            <img src="{{ asset('storage/'.$umkm->gambar) }}" class="img-fluid rounded mb-3 w-100" style="max-height: 400px; object-fit: cover;">
        @endif
        
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ $umkm->nama_umkm }}</h2>
            
            @if($umkm->status_buka == 'Buka')
                <span class="badge bg-success fs-6">BUKA SEKARANG</span>
            @elseif($umkm->status_buka == 'Tutup')
                <span class="badge bg-danger fs-6">TUTUP</span>
            @else
                <span class="badge bg-secondary fs-6">Info Jam Lihat Deskripsi</span>
            @endif
        </div>

        <div class="mb-3">
            <span class="badge bg-secondary">{{ $umkm->kategori }}</span>
            @if($umkm->is_delivery)
                <span class="badge bg-primary">Bisa Delivery</span>
            @else
                <span class="badge bg-warning text-dark">Ambil Sendiri (Pick Up)</span>
            @endif

            <span class="text-warning fw-bold ms-2">
                <i class="bi bi-star-fill"></i> {{ $umkm->rata_rata_rating }} / 5.0
                <span class="text-muted fw-normal">({{ $umkm->reviews->count() }} Ulasan)</span>
            </span>
        </div>
        
        <p>{{ $umkm->deskripsi }}</p>
        <div class="alert alert-info">
            <strong>Jam Operasional:</strong> {{ $umkm->jam_operasional }}
        </div>

        <hr>

        <h4 class="mb-3">Ulasan Pelanggan</h4>
        @forelse($umkm->reviews as $review)
            <div class="card mb-2 bg-light border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold mb-1">{{ $review->user->name }}</h6>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-warning small mb-2">
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <p class="mb-0 text-dark small">{{ $review->komentar }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada ulasan untuk UMKM ini.</p>
        @endforelse
    </div>

    <div class="col-md-4">
        
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

        <div class="card shadow mb-4 sticky-top" style="top: 20px; z-index: 10;">
            <div class="card-header bg-success text-white">
                <i class="bi bi-whatsapp"></i> Buat Pesanan WhatsApp
            </div>
            <div class="card-body">
                @auth
                    <form action="{{ route('umkm.order', $umkm->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Tulis Pesanan Anda</label>
                            <textarea name="pesanan" class="form-control" rows="4" placeholder="Contoh: 1 Nasi Goreng Pedas, 2 Es Teh Manis" required></textarea>
                        </div>

                        @if($umkm->is_delivery)
                            <div class="mb-3">
                                <label class="form-label">Alamat Pengantaran</label>
                                <input type="text" name="alamat" class="form-control" placeholder="Jl. Mawar No. 12" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                Kirim Pesan & Antar
                            </button>
                        @else
                            <div class="alert alert-warning py-2 text-center small">
                                UMKM ini tidak menyediakan delivery. Silakan pesan untuk diambil sendiri.
                            </div>
                            <input type="hidden" name="is_pickup" value="1">
                            <button type="submit" class="btn btn-primary w-100">
                                Pesan & Ambil Sendiri
                            </button>
                        @endif

                    </form>
                @else
                    <div class="text-center py-3">
                        <p class="mb-3">Silakan Login untuk memesan.</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-success w-100">Login Sekarang</a>
                    </div>
                @endauth
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-star"></i> Beri Ulasan & Rating
            </div>
            <div class="card-body">
                @auth
                    <form action="{{ route('umkm.review', $umkm->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Rating Bintang</label>
                            <select name="rating" class="form-select" required>
                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                                <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                <option value="2">⭐⭐ (Kurang)</option>
                                <option value="1">⭐ (Buruk)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Komentar (Opsional)</label>
                            <textarea name="komentar" class="form-control" rows="2" placeholder="Bagaimana rasa makanannya?"></textarea>
                        </div>
                        <button class="btn btn-dark w-100">Kirim Ulasan</button>
                    </form>
                @else
                    <div class="text-center">
                        <p class="small text-muted">Ingin memberi ulasan?</p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark">Login Disini</a>
                    </div>
                @endauth
            </div>
        </div>

    </div>
</div>
@endsection