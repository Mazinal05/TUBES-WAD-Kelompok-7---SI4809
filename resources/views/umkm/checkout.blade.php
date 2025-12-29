@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="mb-4">
                <a href="{{ route('umkm.show', $umkm->id) }}" class="text-decoration-none text-secondary small mb-2 d-block">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu
                </a>
                <h3 class="fw-bold">Konfirmasi Pesanan</h3>
                <p class="text-muted">Pastikan pesanan Anda sudah benar sebelum lanjut ke WhatsApp.</p>
            </div>

            <!-- Order Summary Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Rincian Pesanan - {{ $umkm->nama_umkm }}</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @php $total = 0; $pesananText = "Halo, saya ingin pesan:\n"; @endphp
                        @foreach($cartData as $name => $item)
                        @if($item['qty'] > 0)
                            @php 
                                $subtotal = $item['qty'] * $item['price'];
                                $total += $subtotal;
                                $pesananText .= "- {$item['qty']}x {$name} (@ Rp " . number_format($item['price'], 0, ',', '.') . ")\n";
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $name }}</h6>
                                    <small class="text-muted">{{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</small>
                                </div>
                                <span class="fw-bold text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </li>
                        @endif
                        @endforeach
                        
                        @php 
                            $pesananText .= "\nTotal Estimasi: Rp " . number_format($total, 0, ',', '.');
                        @endphp
                    </ul>
                    <div class="p-4 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Total Pembayaran</span>
                            <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <form action="{{ route('umkm.order', $umkm->id) }}" method="POST">
                @csrf
                <input type="hidden" name="pesanan" value="{{ $pesananText }}">
                
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Informasi Pengiriman</h6>
                        
                        @if($umkm->is_delivery)
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Metode</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="is_pickup" id="delivery" value="0" checked onchange="toggleAddress(true)">
                                    <label class="btn btn-outline-danger rounded-pill px-4 fw-bold" for="delivery">
                                        <i class="bi bi-truck me-1"></i> Delivery
                                    </label>

                                    <input type="radio" class="btn-check" name="is_pickup" id="pickup" value="1" onchange="toggleAddress(false)">
                                    <label class="btn btn-outline-danger rounded-pill px-4 fw-bold" for="pickup">
                                        <i class="bi bi-bag me-1"></i> Ambil Sendiri
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3" id="addressContainer">
                                <label class="form-label small fw-bold text-muted">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control bg-light border-0" rows="3" placeholder="Contoh: Jl. Sukabiryu No. 12, Pagar Hitam..." required></textarea>
                            </div>
                        @else
                            <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning mb-0">
                                <i class="bi bi-info-circle me-2"></i> UMKM ini hanya melayani <strong>Ambil Sendiri (Pick Up)</strong>.
                            </div>
                            <input type="hidden" name="is_pickup" value="1">
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill py-3 shadow hover-scale">
                    <i class="bi bi-whatsapp me-2"></i> Lanjut ke WhatsApp
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleAddress(show) {
        const addr = document.getElementById('addressContainer');
        const input = addr.querySelector('textarea');
        if(show) {
            addr.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            addr.style.display = 'none';
            input.removeAttribute('required');
        }
    }
</script>
<script>
    document.querySelector('form').addEventListener('submit', function() {
        const UMKM_ID = "{{ $umkm->id }}";
        const STORAGE_KEY = `makanapa_cart_${UMKM_ID}`;
        localStorage.removeItem(STORAGE_KEY);
    });
</script>
<style>
    .hover-scale:hover { transform: scale(1.02); transition: 0.2s; }
    
    /* Custom Radio Buttons */
    .btn-check:checked + .btn-outline-danger {
        background-color: var(--brand-red);
        color: white;   
        border-color: var(--brand-red);
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.2);
    }
    .btn-outline-danger {
        color: var(--brand-dark);
        border-color: #dee2e6;
        background-color: white;
    }
    .btn-outline-danger:hover {
        background-color: #fff5f5;
        border-color: var(--brand-red);
        color: var(--brand-red);
    }
</style>
@endsection
