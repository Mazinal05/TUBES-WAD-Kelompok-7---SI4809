@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 text-center pt-5 pb-3">
                <img src="{{ asset('images/makanapa logo removebg.png') }}" alt="MakanApa?" height="120" class="mb-3">
                <p class="text-secondary small">Buat akun baru dan mulai jelajahi kuliner!</p>
            </div>
            <div class="card-body px-5 pb-5">
                
                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted ms-2">NAMA LENGKAP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                                <i class="bi bi-person text-primary"></i>
                            </span>
                            <input type="text" name="name" class="form-control bg-light border-0 rounded-end-pill py-3" placeholder="Nama Anda" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted ms-2">EMAIL ADDRESS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                                <i class="bi bi-envelope text-primary"></i>
                            </span>
                            <input type="email" name="email" class="form-control bg-light border-0 rounded-end-pill py-3" placeholder="name@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted ms-2">PASSWORD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                                <i class="bi bi-lock text-primary"></i>
                            </span>
                            <input type="password" name="password" id="regPassword" class="form-control bg-light border-0 py-3" placeholder="Minimal 8 karakter" required>
                            <button class="btn bg-light border-0 rounded-end-pill pe-3 text-primary" type="button" onclick="togglePassword('regPassword', this)">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted ms-2">KONFIRMASI PASSWORD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                                <i class="bi bi-check2-circle text-primary"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="regConfirm" class="form-control bg-light border-0 py-3" placeholder="Ulangi password" required>
                            <button class="btn bg-light border-0 rounded-end-pill pe-3 text-primary" type="button" onclick="togglePassword('regConfirm', this)">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm transition-all">
                            DAFTAR SEKARANG
                        </button>
                    </div>

                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-muted mb-0">Sudah punya akun?</p>
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none text-primary">Login Disini</a>
                </div>
            </div>
        </div>
        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} MakanApa? All rights reserved.
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1.5rem !important; }
    .rounded-start-pill { border-top-left-radius: 50rem !important; border-bottom-left-radius: 50rem !important; }
    .rounded-end-pill { border-top-right-radius: 50rem !important; border-bottom-right-radius: 50rem !important; }
    .form-control:focus { box-shadow: none; background-color: #fff; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4) !important; }
</style>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endsection