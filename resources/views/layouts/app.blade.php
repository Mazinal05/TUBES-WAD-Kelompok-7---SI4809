<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MakanApa?</title>
    <link rel="icon" type="image/png" href="{{ asset('images/makanapa logo removebg.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- BRANDING: MakanApa? -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-red: #D32F2F;
            --brand-dark: #212529;
            --brand-light: #fbe9e7; /* Light Red Tint */
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: var(--brand-dark);
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Poppins', sans-serif;
        }

        /* BRAND OVERRIDES */
        .text-primary { color: var(--brand-red) !important; }
        .bg-primary { background-color: var(--brand-red) !important; }
        
        .btn-primary { 
            background-color: var(--brand-red) !important; 
            border-color: var(--brand-red) !important;
        }
        
        .btn-primary:hover, .btn-primary:active {
            background-color: #b71c1c !important;
            border-color: #b71c1c !important;
        }

        .btn-outline-primary {
            color: var(--brand-red) !important;
            border-color: var(--brand-red) !important;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--brand-red) !important;
            color: white !important;
        }

        /* Navbar Styling */
        .navbar {
            border-bottom: 2px solid var(--brand-red);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--brand-red) !important;
            text-shadow: 1px 1px 0px #000;
            letter-spacing: -0.5px;
        }
        
        /* Accent tweaks */
        .badge.bg-primary {
            background-color: var(--brand-red) !important;
        }
        
        /* Sticky Footer/Sidebar polish if needed via global */
        .text-warning {
            color: #ffc107 !important; /* Keep yellow standard */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/makanapa logo removebg.png') }}" alt="MakanApa?" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link text-secondary">Halo, {{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                             <a href="{{ route('favorites.index') }}" class="btn btn-sm btn-outline-danger mt-1 me-2 rounded-pill">
                                <i class="bi bi-heart-fill"></i> Favorit
                            </a>
                        </li>
                        @if(Auth::user()->role == 'admin')
                            <li class="nav-item">
                                <a href="{{ route('admin.umkms.index') }}" class="btn btn-sm btn-warning mt-1 me-2">Admin Panel</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger mt-1">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-primary mt-1">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>