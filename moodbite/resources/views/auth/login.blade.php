@extends('layouts.app')

@section('title', 'Login - MoodBite')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-5">
            <h2 style="color: #FF6B8B;">Login ke MoodBite</h2>
            <p class="text-muted">Masuk untuk menemukan makanan sesuai mood-mu</p>
        </div>
        
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="{{ old('email') }}" required placeholder="email@example.com">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required 
                               placeholder="••••••••">
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-decoration-none" style="color: #FF6B8B;">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <a href="{{ url('/') }}" class="text-decoration-none" style="color: #666;">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</div>
@endsection