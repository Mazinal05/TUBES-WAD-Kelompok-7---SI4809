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
            <span class="badge bg-secondary">
                {{ is_array($umkm->kategori) ? implode(', ', $umkm->kategori) : $umkm->kategori }}
            </span>
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

        <div class="alert alert-info mb-4 d-flex align-items-center">
            <i class="bi bi-clock-fill me-2 fs-5"></i>
            <div>
                <strong>Jam Operasional:</strong><br>
                {!! nl2br(e($umkm->jam_operasional)) !!}
            </div>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold">Alamat & Lokasi</h5>
            <p class="mb-2 text-muted"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $umkm->alamat }}</p>
            
            @if($umkm->koordinat || $umkm->alamat)
                @php
                    $isUrl = filter_var($umkm->koordinat, FILTER_VALIDATE_URL);
                    
                    // Tentukan URL untuk tombol "Buka di Google Maps"
                    if ($isUrl) {
                        $mapsUrl = $umkm->koordinat;
                    } elseif ($umkm->koordinat) {
                        $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($umkm->koordinat);
                    } else {
                        // Fallback ke alamat jika koordinat kosong
                        $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($umkm->alamat);
                    }

                    // Tentukan Query untuk Embed Map (Iframe)
                    // Link pendek (seperti maps.app.goo.gl) tidak bisa di-embed langsung, jadi pakai alamat sebagai fallback visual
                    if ($isUrl) {
                        $embedQuery = $umkm->alamat;
                    } elseif ($umkm->koordinat) {
                        $embedQuery = $umkm->koordinat;
                    } else {
                        $embedQuery = $umkm->alamat;
                    }
                @endphp
                
                <!-- Map Preview Card -->
                <a href="{{ $mapsUrl }}" target="_blank" class="text-decoration-none">
                    <div class="rounded-3 overflow-hidden position-relative border" style="height: 150px; background-color: #e8eaed;">
                        <!-- Real Map Embed as Background -->
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 
                            style="pointer-events: none; filter: contrast(1.1) saturate(0.8);" 
                            src="https://maps.google.com/maps?q={{ urlencode($embedQuery) }}&t=m&z=15&output=embed">
                        </iframe>
                        
                        <!-- Overlay to ensure text legibility if map is busy -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(255,255,255,0.1);"></div>

                        <!-- External Link Icon -->
                        <div class="position-absolute top-0 end-0 m-2 bg-white text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px;">
                            <i class="bi bi-box-arrow-up-right small"></i>
                        </div>
                        
                        <!-- Label Overlay -->
                        <div class="position-absolute bottom-0 start-0 w-100 bg-white py-2 px-3 border-top">
                            <small class="fw-bold text-dark"><i class="bi bi-map me-1"></i> Buka di Google Maps</small>
                        </div>
                    </div>
                </a>
            @endif
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
        
        <!-- DAFTAR MENU SECTION -->
        <h4 class="mt-5 mb-3 fw-bold">Daftar Menu</h4>
        <div class="row mb-5">
            @forelse($umkm->menus as $menu)
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-2 d-flex gap-3 align-items-center">
                        <!-- Menu Image -->
                        <div style="width: 80px; height: 80px; flex-shrink: 0;">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/'.$menu->gambar) }}" class="rounded w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-cup-hot"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Menu Details -->
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $menu->nama_menu }}</h6>
                            <small class="text-muted d-block lh-sm mb-1">{{ Str::limit($menu->deskripsi, 50) }}</small>
                            <div class="fw-bold text-success">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                        </div>

                        <!-- Action -->
                        <div>
                            <button class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center shadow btn-add-menu" 
                                    style="width: 40px; height: 40px; transition: all 0.2s;"
                                    data-name="{{ $menu->nama_menu }}"
                                    data-price="{{ $menu->harga }}">
                                <i class="bi bi-plus-lg" style="color: #ffffff; font-size: 1.5rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-muted">Belum ada menu yang tersedia.</div>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = {};
            const textArea = document.querySelector('textarea[name="pesanan"]');
            
            // Function to generate text
            function updateTextArea() {
                let text = "Saya ingin pesan:\n";
                let hasItems = false;
                let total = 0;

                for (let [name, item] of Object.entries(cart)) {
                    if(item.qty > 0) {
                        const subtotal = item.qty * item.price;
                        text += `- ${item.qty}x ${name} (@ ${formatRupiah(item.price)})\n`;
                        total += subtotal;
                        hasItems = true;
                    }
                }

                if(hasItems) {
                    text += `\nTotal Estimasi: ${formatRupiah(total)}`;
                    textArea.value = text;
                }
            }

            // Helper Rupiah
            function formatRupiah(num) {
                return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Event Listeners
            document.querySelectorAll('.btn-add-menu').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.dataset.name;
                    const price = parseInt(this.dataset.price);

                    if(!cart[name]) {
                        cart[name] = { qty: 0, price: price };
                    }
                    cart[name].qty++;
                    
                    // Visual feedback
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-check-lg fs-5"></i>';
                    this.classList.replace('btn-primary', 'btn-success');
                    
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                        this.classList.replace('btn-success', 'btn-primary');
                    }, 500);

                    updateTextArea();
                });
            });
        });
    </script>

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
                            <textarea name="pesanan" class="form-control" rows="4" placeholder=": 1 Nasi Goreng Pedas, 2 Es Teh Manis" required></textarea>
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