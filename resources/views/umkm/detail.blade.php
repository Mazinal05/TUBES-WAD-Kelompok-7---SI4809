@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        position: relative;
        height: 350px;
        background-color: #333;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 30px;
    }
    @media (max-width: 768px) {
        .hero-section {
            height: 250px;
            border-radius: 15px;
        }
        .hero-section h1 {
            font-size: 2rem !important; /* Adjusted title size */
        }
        .glass-badge {
            font-size: 0.75rem !important; /* Smaller badges */
            padding: 4px 10px !important;
        }
        .hero-overlay {
            padding: 20px !important; /* Less padding */
        }
        .badge.fs-6 {
            font-size: 0.8rem !important; /* Smaller status badge */
            padding: 0.5em 1em !important;
        }
    }
    .hero-bg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.8;
    }
    .hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 30px;
        color: white;
    }
    .glass-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .sticky-sidebar {
        position: sticky;
        top: 20px;
        z-index: 100;
    }
    .menu-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #f0f0f0;
    }
    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .btn-add-menu {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-add-menu:active {
        transform: scale(0.9);
    }
</style>

<!-- HERO SECTION -->
<div class="hero-section shadow-sm">
    @if($umkm->gambar)
        <img src="{{ asset('storage/'.$umkm->gambar) }}" class="hero-bg">
    @else
        <div class="hero-bg bg-secondary d-flex align-items-center justify-content-center">
            <i class="bi bi-shop fs-1 text-white-50"></i>
        </div>
    @endif
    
    <div class="hero-overlay">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="display-5 fw-bold mb-2">
                        {{ $umkm->nama_umkm }}
                    </h1>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="glass-badge">
                            <i class="bi bi-tag-fill me-1"></i> {{ is_array($umkm->kategori) ? implode(', ', $umkm->kategori) : $umkm->kategori }}
                        </span>
                        <span class="glass-badge">
                            <i class="bi bi-star-fill text-warning me-1"></i> {{ $umkm->rata_rata_rating }} / 5.0
                        </span>
                    </div>
                </div>
                <div>
                     @if($umkm->status_buka == 'Buka')
                        <span class="badge bg-success rounded-pill px-4 py-2 fs-6 shadow">BUKA SEKARANG</span>
                    @elseif($umkm->status_buka == 'Tutup')
                        <span class="badge bg-danger rounded-pill px-4 py-2 fs-6 shadow">TUTUP</span>
                    @else
                        <span class="badge bg-secondary rounded-pill px-4 py-2 fs-6 shadow">Info Jam Lihat Deskripsi</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT COLUMN: Main Content -->
    <div class="col-lg-8">
        
        <!-- Description -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Tentang UMKM</h5>
                    @auth
                        <form action="{{ route('umkm.favorite', $umkm->id) }}" method="POST">
                            @csrf
                            @if(Auth::user()->favorites->contains($umkm->id))
                                <button type="submit" class="btn btn-danger rounded-pill btn-sm px-3 shadow-sm">
                                    <i class="bi bi-heart-fill me-1"></i> Tersimpan
                                </button>
                            @else
                                <button type="submit" class="btn btn-outline-danger rounded-pill btn-sm px-3 shadow-sm">
                                    <i class="bi bi-heart me-1"></i> Simpan ke Favorit
                                </button>
                            @endif
                        </form>
                    @endauth
                </div>
                <p class="text-secondary mb-0" style="line-height: 1.7;">{{ $umkm->deskripsi }}</p>
            </div>
        </div>

        <!-- MENU SECTION -->
        <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
            <h4 class="fw-bold mb-0 text-dark">Daftar Menu</h4>
            <div class="bg-light px-3 py-1 rounded-pill border small text-muted">
                <i class="bi bi-check-circle-fill text-success me-1"></i> Pesan lewat WhatsApp
            </div>
        </div>
        
        @php
            $groupedMenus = $umkm->menus->groupBy('kategori');
            $orderedCategories = ['Makanan Berat', 'Makanan Ringan', 'Minuman'];
        @endphp

        @foreach($orderedCategories as $category)
            @if(isset($groupedMenus[$category]) && $groupedMenus[$category]->count() > 0)
                <div class="mb-5">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom border-light">
                        {{ $category }}
                    </h5>
                    <div class="row g-3">
                        @foreach($groupedMenus[$category] as $menu)
                        <div class="col-md-6">
                            <div class="card menu-card h-100 bg-white border-0 shadow-sm rounded-4 position-relative overflow-hidden">
                                <div class="card-body p-3 d-flex gap-3 align-items-center">
                                    <!-- Image -->
                                    <div class="position-relative rounded-3 overflow-hidden flex-shrink-0" style="width: 85px; height: 85px;">
                                        @if($menu->gambar)
                                            <img src="{{ asset('storage/'.$menu->gambar) }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-cup-hot h3 mb-0 opacity-25"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="fw-bold mb-1 text-truncate">{{ $menu->nama_menu }}</h6>
                                        <p class="text-muted small mb-2 text-truncate" style="max-width: 90%;">{{ Str::limit($menu->deskripsi, 40) }}</p>
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="fw-bold text-dark">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                                            
                                            <!-- Logic Action -->
                                            <div class="menu-action-container" data-name="{{ $menu->nama_menu }}" data-price="{{ $menu->harga }}">
                                                <!-- State 1: Add Button -->
                                                <button class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center shadow btn-add-initial" 
                                                        style="width: 38px; height: 38px;">
                                                    <i class="bi bi-plus-lg text-white"></i>
                                                </button>

                                                <!-- State 2: Quantity Control -->
                                                <div class="qty-control d-none align-items-center bg-white rounded-pill border shadow px-1 py-1">
                                                    <button class="btn btn-sm btn-light text-danger rounded-circle p-0 d-flex align-items-center justify-content-center btn-decrease" style="width: 28px; height: 28px;">
                                                        <i class="bi bi-dash-lg"></i>
                                                    </button>
                                                    <span class="mx-2 fw-bold small qty-display text-dark" style="min-width: 20px; text-align: center;">1</span>
                                                    <button class="btn btn-sm btn-light text-success rounded-circle p-0 d-flex align-items-center justify-content-center btn-increase" style="width: 28px; height: 28px;">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if($umkm->menus->isEmpty())
             <div class="text-muted text-center py-5 bg-light rounded-4">
                <i class="bi bi-clipboard-x fs-1 mb-3 d-block opacity-25"></i>
                Belum ada menu yang tersedia.
            </div>
        @endif

        <!-- REVIEWS SECTION -->
        <div class="mt-5">
            <h4 class="fw-bold mb-4">Ulasan Pelanggan <span class="text-muted fs-6 fw-normal">({{ $umkm->reviews->count() }})</span></h4>
            
            <!-- Review Summary Chart -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center border-end">
                            <h1 class="display-3 fw-bold text-dark mb-0">{{ $umkm->rata_rata_rating }}</h1>
                            <div class="text-warning fs-5 mb-2">
                                @for($i=1; $i<=5; $i++)
                                    <i class="bi bi-star{{ $i <= round($umkm->rata_rata_rating) ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <p class="text-secondary small mb-0">{{ $umkm->reviews->count() }} Ulasan</p>
                        </div>
                        <div class="col-md-8 ps-md-5">
                            @php
                                $totalReviews = $umkm->reviews->count();
                                $counts = [
                                    5 => $umkm->reviews->where('rating', 5)->count(),
                                    4 => $umkm->reviews->where('rating', 4)->count(),
                                    3 => $umkm->reviews->where('rating', 3)->count(),
                                    2 => $umkm->reviews->where('rating', 2)->count(),
                                    1 => $umkm->reviews->where('rating', 1)->count(),
                                ];
                            @endphp

                            @foreach([5,4,3,2,1] as $star)
                                @php
                                    $percentage = $totalReviews > 0 ? ($counts[$star] / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <span class="small text-muted fw-bold me-3" style="width: 20px;">{{ $star }}</span>
                                    <div class="progress flex-grow-1 rounded-pill" style="height: 8px; background-color: #f0f0f0;">
                                        <div class="progress-bar bg-danger rounded-pill" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="small text-muted ms-3 text-end" style="width: 30px;">{{ $counts[$star] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horizontal Scroll Container -->
            <div class="d-flex overflow-auto pb-3 gap-3" style="scroll-behavior: smooth;">
                
                @forelse($umkm->reviews->sortByDesc('created_at') as $review)
                    <div class="flex-shrink-0" style="width: 85%; max-width: 350px;">
                        <div class="card border-0 bg-light rounded-4 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm me-2" style="width: 35px; height: 35px;">
                                        <span class="fw-bold text-primary small">{{ substr($review->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 small">{{ $review->user->name }}</h6>
                                        <div class="text-warning" style="font-size: 0.7rem;">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted ms-auto" style="font-size: 0.7rem;">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 text-dark small bg-white p-2 rounded-3 border-0 shadow-sm" style="max-height: 80px; overflow-y: auto;">{{ $review->komentar }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-100 text-center text-muted py-3">Belum ada ulasan.</div>
                @endforelse
            </div>

            <!-- Review Form -->
             <div class="p-4 rounded-4 bg-white shadow-sm border">
                <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2 text-warning"></i>Bagikan Pengalaman Anda</h5>
                @auth
                    @php
                        $userReview = $umkm->reviews->where('user_id', auth()->id())->first();
                    @endphp

                    @if($userReview)
                        <div class="text-center py-4 bg-light rounded-3 border border-success border-opacity-25">
                            <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark">Terima Kasih!</h6>
                            <p class="text-muted small mb-0">Ulasan Anda telah diterbitkan.</p>
                        </div>
                    @else
                        <form action="{{ route('umkm.review', $umkm->id) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small text-muted fw-bold">RATING</label>
                                    <select name="rating" class="form-select border-0 bg-light py-2" required>
                                        <option value="5">⭐⭐⭐⭐⭐ </option>
                                        <option value="4">⭐⭐⭐⭐ </option>
                                        <option value="3">⭐⭐⭐ </option>
                                        <option value="2">⭐⭐ </option>
                                        <option value="1">⭐ </option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small text-muted fw-bold">KOMENTAR</label>
                                    <textarea name="komentar" class="form-control border-0 bg-light" rows="1" placeholder="Ceritakan pengalamanmu..." style="resize: none;"></textarea>
                                </div>
                                <div class="col-12 text-end mt-2">
                                    <button class="btn btn-dark px-4 rounded-pill">Kirim Ulasan <i class="bi bi-send ms-1"></i></button>
                                </div>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                        <span class="text-muted small">Ingin membagikan ulasan?</span>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">Login</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Sticky Sidebar -->
    <div class="col-lg-4">
        <div class="sticky-sidebar">
            
            <!-- INFO CARD -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-geo-alt me-2 text-danger"></i>Lokasi & Jam Buka</h6>
                </div>
                
                @if($umkm->koordinat || $umkm->alamat)
                    @php
                       $isUrl = filter_var($umkm->koordinat, FILTER_VALIDATE_URL);
                       if ($isUrl) $mapsUrl = $umkm->koordinat;
                       elseif ($umkm->koordinat) $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($umkm->koordinat);
                       else $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($umkm->alamat);

                       $embedQuery = ($isUrl || !$umkm->koordinat) ? $umkm->alamat : $umkm->koordinat;
                    @endphp
                    
                    <div class="position-relative" style="height: 200px;">
                         <iframe 
                            width="100%" height="100%" frameborder="0" style="border:0; filter: contrast(1.1) saturate(0.8);"
                            src="https://maps.google.com/maps?q={{ urlencode($embedQuery) }}&t=m&z=15&output=embed">
                        </iframe>
                        <a href="{{ $mapsUrl }}" target="_blank" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-decoration-none" style="background: rgba(0,0,0,0.05); transition: 0.3s; opacity: 0; hover: opacity: 1;">
                             <span class="btn btn-light shadow-sm rounded-pill btn-sm"><i class="bi bi-map me-1"></i> Buka Maps</span>
                        </a>
                    </div>
                @endif
                
                <div class="card-body">
                    <p class="small text-muted mb-3"><i class="bi bi-geo-alt-fill me-1 text-danger"></i> {{ $umkm->alamat }}</p>
                    <div class="p-3 bg-light rounded-3">
                         <div class="d-flex align-items-center mb-2">
                             <i class="bi bi-clock-fill me-2 text-primary"></i>
                             <strong class="small">Jam Operasional</strong>
                         </div>
                         <div class="small text-secondary" style="white-space: pre-line;">{!! e($umkm->jam_operasional) !!}</div>
                    </div>
                </div>
            </div>

            <!-- ORDER CARD -->
            <!-- ORDER CARD (SHOPPING CART) -->
            <!-- ORDER CARD (SHOPPING CART) -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-primary text-white p-4 border-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-cart-fill me-2"></i>Pesanan Saya</h5>
                    <p class="small text-white-50 mb-0 mt-1">Item yang Anda pilih akan muncul di sini.</p>
                </div>
                <div class="card-body p-4">
                    @auth
                        <form action="{{ route('umkm.checkout', $umkm->id) }}" method="POST" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="cart_json" id="cartJsonInput">
                            
                            <!-- DYNAMIC CART LIST -->
                            <div id="cartContainer" class="mb-3">
                                <!-- Empty State -->
                                <div id="emptyCart" class="text-center py-4 text-muted">
                                    <i class="bi bi-basket3 fs-1 opacity-25"></i>
                                    <p class="small mt-2 mb-0">Keranjang masih kosong.<br>Pilih menu di samping!</p>
                                </div>
                                <!-- List Items (Injected by JS) -->
                                <ul id="cartList" class="list-group list-group-flush d-none"></ul>
                            </div>

                            <!-- TOTAL SECTION -->
                            <div id="totalSection" class="d-none pt-3 border-top border-dashed">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-secondary fw-bold small">Total Estimasi</span>
                                    <h4 class="fw-bold text-primary mb-0" id="cartTotal">Rp 0</h4>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-3 shadow-sm transition-all hover-scale" id="btnCheckout" disabled>
                                    Lanjut Checkout <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/login-3305943-2757111.png" alt="Login" height="100" class="mb-3 opacity-75">
                            <p class="text-secondary small mb-3">Silakan login untuk mulai memesan.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 fw-bold rounded-pill">Login Sekarang</a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- MOBILE STICKY BOTTOM BAR (Hidden on Desktop) -->
            @auth
            <div id="mobileBottomBar" class="fixed-bottom bg-white border-top shadow-lg p-3 d-lg-none d-none slide-up">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <small class="text-muted d-block">Total Pembayaran</small>
                        <h5 class="fw-bold text-primary mb-0" id="mobileTotal">Rp 0</h5>
                    </div>
                    <button onclick="document.getElementById('checkoutForm').submit()" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        Lanjut <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
            @endauth

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let cart = {};
        const UMKM_ID = "{{ $umkm->id }}";
        const STORAGE_KEY = `makanapa_cart_${UMKM_ID}`;

        const cartList = document.getElementById('cartList');
        const emptyCart = document.getElementById('emptyCart');
        const totalSection = document.getElementById('totalSection');
        const cartTotal = document.getElementById('cartTotal');
        const btnCheckout = document.getElementById('btnCheckout');
        
        // 1. Load from Storage
        if (localStorage.getItem(STORAGE_KEY)) {
            try {
                cart = JSON.parse(localStorage.getItem(STORAGE_KEY));
                // Delay UI update slightly to ensure DOM is ready
                setTimeout(() => {
                    updateUI();
                    // Restore Buttons State
                    document.querySelectorAll('.menu-action-container').forEach(container => {
                        const name = container.dataset.name;
                        renderControl(container, name);
                    });
                }, 50);
            } catch (e) {
                console.error("Error loading cart:", e);
                localStorage.removeItem(STORAGE_KEY);
            }
        }
        
        // Helper Rupiah
        function formatRupiah(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Update UI Cart & Text Area
        function updateUI() {
            let hasItems = false;
            let total = 0;
            
            // Clear List UI
            cartList.innerHTML = '';
            
            for (let [name, item] of Object.entries(cart)) {
                if(item.qty > 0) {
                    const subtotal = item.qty * item.price;
                    total += subtotal;
                    hasItems = true;

                    // Add to Visual List (Frontend)
                    const li = document.createElement('li');
                    li.className = "list-group-item d-flex justify-content-between align-items-center px-0 border-light";
                    li.innerHTML = `
                        <div class="ms-2 me-auto">
                            <div class="fw-bold text-dark small">${name}</div>
                            <div class="text-muted" style="font-size: 0.8rem;">${item.qty} x ${formatRupiah(item.price)}</div>
                        </div>
                        <span class="fw-bold text-primary small">${formatRupiah(subtotal)}</span>
                    `;
                    cartList.appendChild(li);
                }
            }

            // Sync to Hidden Input
            const jsonInput = document.getElementById('cartJsonInput');
            if(jsonInput) jsonInput.value = JSON.stringify(cart);

            // Finalize State
            const mobileBar = document.getElementById('mobileBottomBar');
            const mobileTotal = document.getElementById('mobileTotal');

            if(hasItems) {
                // Show Cart, Hide Empty
                emptyCart.classList.add('d-none');
                cartList.classList.remove('d-none');
                totalSection.classList.remove('d-none');
                
                // Update Total Display
                const formattedTotal = formatRupiah(total);
                if(cartTotal) cartTotal.textContent = formattedTotal;
                if(mobileTotal) mobileTotal.textContent = formattedTotal;

                // Create Mobile Bottom Bar Visibility (Mobile Only)
                if(mobileBar) mobileBar.classList.remove('d-none');

                // Enable Checkout
                if(btnCheckout) btnCheckout.removeAttribute('disabled');
            } else {
                // Hide Cart, Show Empty
                emptyCart.classList.remove('d-none');
                cartList.classList.add('d-none');
                totalSection.classList.add('d-none');
                if(mobileBar) mobileBar.classList.add('d-none');
                
                // Disable Checkout
                if(btnCheckout) btnCheckout.setAttribute('disabled', 'disabled');
            }
        }

        // Render Control State (Plus/Minus Buttons)
        function renderControl(container, name) {
            const btnAdd = container.querySelector('.btn-add-initial');
            const qtyControl = container.querySelector('.qty-control');
            const qtyDisplay = container.querySelector('.qty-display');
            
            const qty = cart[name] ? cart[name].qty : 0;

            if (qty > 0) {
                btnAdd.classList.add('d-none');
                qtyControl.classList.remove('d-none');
                qtyControl.classList.add('d-flex');
                qtyDisplay.textContent = qty;
            } else {
                btnAdd.classList.remove('d-none');
                qtyControl.classList.add('d-none');
                qtyControl.classList.remove('d-flex');
            }
        }

        // Update Cart Logic
        function updateCart(name, price, delta, container) {
            if (!cart[name]) {
                cart[name] = { qty: 0, price: price };
            }
            
            cart[name].qty += delta;
            if (cart[name].qty < 0) cart[name].qty = 0;

            renderControl(container, name);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cart)); // Save to Storage
            updateUI();
        }

        // Event Listeners
        document.querySelectorAll('.menu-action-container').forEach(container => {
            const name = container.dataset.name;
            const price = parseInt(container.dataset.price);

            // Initial Add Button
            container.querySelector('.btn-add-initial').addEventListener('click', () => {
               updateCart(name, price, 1, container);
            });

            // Increase Button
            container.querySelector('.btn-increase').addEventListener('click', () => {
                updateCart(name, price, 1, container);
            });

            // Decrease Button
            container.querySelector('.btn-decrease').addEventListener('click', () => {
                updateCart(name, price, -1, container);
            });
        });
    });
</script>

<style>
    /* Desktop: Sticky Sidebar */
    @media (min-width: 992px) {
        .sticky-sidebar {
            position: sticky;
            top: 100px; /* Offset for navbar */
            z-index: 10;
        }
    }
    
    /* Mobile: Adjustments */
    @media (max-width: 991px) {
        .sticky-sidebar {
            position: static;
        }
        .menu-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection