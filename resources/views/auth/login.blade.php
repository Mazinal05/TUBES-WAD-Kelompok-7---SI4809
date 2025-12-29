@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 text-center pt-5 pb-3">
                <img src="{{ asset('images/makanapa logo removebg.png') }}" alt="MakanApa?" height="120" class="mb-3">
                <p class="text-secondary small">Selamat datang kembali! Silakan login untuk melanjutkan.</p>
            </div>
            <div class="card-body px-5 pb-5">
                
                @if ($errors->any())
                    <div class="alert alert-danger rounded-40 border-0 shadow-sm mb-4">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted ms-2">EMAIL ADDRESS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                                <i class="bi bi-envelope text-primary"></i>
                            </span>
                            <input type="email" name="email" class="form-control bg-light border-0 rounded-end-pill py-3" placeholder="name@example.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted ms-2">PASSWORD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                                <i class="bi bi-lock text-primary"></i>
                            </span>
                            <input type="password" name="password" id="passwordInput" class="form-control bg-light border-0 py-3" placeholder="Masukan password anda" required>
                            <button class="btn bg-light border-0 rounded-end-pill pe-3 text-primary" type="button" onclick="togglePassword('passwordInput', this)">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm transition-all">
                            LOGIN
                            <i class="bi bi-arrow-right-short fs-4 ms-1 align-middle"></i>
                        </button>
                    </div>

                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-muted mb-0">Belum punya akun?</p>
                    <a href="{{ route('register') }}" class="fw-bold text-decoration-none text-primary">Daftar Sekarang</a>
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