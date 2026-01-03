<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MoodBite')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-pink: #FF6B8B;
            --secondary-yellow: #FFD166;
            --accent-mint: #98FF98;
            --accent-blue: #87CEEB;
            --accent-lilac: #C8A2C8;
            --light-bg: #FFF5F5;
            --text-dark: #555555;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            padding-top: 0;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-lilac));
            box-shadow: 0 4px 20px rgba(255, 107, 139, 0.2);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-brand i {
            margin-right: 10px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            margin: 0 10px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-yellow));
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 107, 139, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-pink);
            color: var(--primary-pink);
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-pink);
            color: white;
            transform: translateY(-3px);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(255, 107, 139, 0.1);
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 107, 139, 0.2);
        }

        .badge {
            border-radius: 15px;
            padding: 6px 12px;
            font-weight: 600;
        }

        main {
            padding: 30px 0;
            min-height: calc(100vh - 200px);
        }

        .footer {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-lilac));
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }

        /* Admin badge kecil */
        .admin-badge {
            background: rgba(255, 209, 102, 0.2);
            border: 1px solid rgba(255, 209, 102, 0.7);
            color: #FFD166;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px;
            margin-left: 8px;
        }
    </style>

    @stack('styles')
</head>

<body>
@auth
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-utensils"></i>MoodBite
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>

                <!-- Cari Makanan -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('recommendations') }}">
                        <i class="fas fa-search me-1"></i>Cari Makanan
                    </a>
                </li>

                <!-- Dropdown User / Admin (PALING KANAN) -->
                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center"
                       href="#" role="button" data-bs-toggle="dropdown">

                        <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}

                        {{-- Admin Badge hanya muncul di kanan --}}
                        @if(Auth::user()->role === 'admin')
                            <span class="admin-badge">
                                <i class="fas fa-shield-halved me-1"></i>ADMIN
                            </span>
                        @elseif(Auth::user()->isPremium())
                            <i class="fas fa-crown ms-1" style="color: #FFD700;"></i>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        {{-- Admin Dashboard hanya ada di dropdown, satu aja --}}
                        @if(Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-chart-line me-2"></i>Admin Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @endif

                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user-circle me-2"></i>Profil Saya
                            </a>
                        </li>

                        {{-- Membership hanya user biasa --}}
                        @if(Auth::user()->role !== 'admin')
                            <li>
                                <a class="dropdown-item" href="{{ route('membership.index') }}">
                                    <i class="fas fa-crown me-2" style="color: #FFD700;"></i>
                                    Membership
                                    @if(Auth::user()->isPremium())
                                        <span class="badge bg-success ms-2">Premium</span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>
@endauth

<main>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

@auth
<footer class="footer">
    <div class="container text-center">
        <p class="mb-0">
            © 2025 MoodBite • Made with <i class="fas fa-heart" style="color: #FFD166;"></i> for food lovers
        </p>
    </div>
</footer>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
