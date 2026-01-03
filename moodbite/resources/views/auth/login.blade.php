@extends('layouts.app')

@section('title', 'Login - MoodBite')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-5">
            <h2 style="color: #FF6B8B; font-weight:800;">Login ke MoodBite</h2>
            <p class="text-muted">Masuk untuk menemukan makanan sesuai mood-mu</p>
        </div>
        
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4">
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control form-control-lg" name="email"
                               value="{{ old('email') }}" required placeholder="email@example.com"
                               style="border-radius: 14px;">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control form-control-lg" name="password" required
                               placeholder="••••••••"
                               style="border-radius: 14px;">
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 14px;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                </form>

                <div class="divider-or my-4">
                    <span>atau masuk dengan</span>
                </div>

                <div class="d-grid">
                    <a href="{{ route('google.redirect') }}" class="btn btn-google btn-lg">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="google-icon">
                        Login dengan Google
                    </a>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-0">Belum punya akun?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #FF6B8B;">
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

<style>
.divider-or{
    position: relative;
    text-align: center;
}
.divider-or span{
    background: #fff;
    padding: 0 15px;
    font-size: 13px;
    font-weight: 700;
    color: #999;
}
.divider-or::before{
    content:"";
    position:absolute;
    top:50%;
    left:0;
    width:100%;
    height:1px;
    background:#eee;
    z-index:-1;
}

.btn-google{
    background:#fff;
    border:1.5px solid #ddd;
    font-weight:800;
    border-radius:14px;
    padding:12px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:12px;
    transition: .25s ease;
}
.btn-google:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0,0,0,.08);
    border-color:#FF6B8B;
}
.google-icon{
    width:20px;
    height:20px;
}
</style>
@endsection
