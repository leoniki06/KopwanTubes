@extends('layouts.app')

@section('title', 'Daftar - MoodBite')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-5">
            <h2 style="color: #FF6B8B;">Daftar MoodBite</h2>
            <p class="text-muted">Bergabunglah untuk menemukan makanan sesuai mood-mu</p>
        </div>
        
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" 
                               value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
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
                        <small class="text-muted">Minimal 8 karakter</small>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required 
                               placeholder="••••••••">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-decoration-none" style="color: #FF6B8B;">
                            Login di sini
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