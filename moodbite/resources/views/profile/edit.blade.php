@extends('layouts.app')

@section('title', 'Edit Profil - MoodBite')

@section('content')
@php
    $user = Auth::user();
@endphp

<div class="container py-4">
    <!-- Header -->
    <div class="edit-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1">Edit Profil</h3>
                <p class="text-muted mb-0">Update data kamu biar makin kece ✨</p>
            </div>

            <a href="{{ route('profile.show') }}" class="btn btn-outline-pink">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-soft border-0">
                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- LEFT: Avatar -->
                            <div class="col-lg-4">
                                <div class="avatar-card">
                                    <div class="text-center">
                                        <div class="avatar-wrap mx-auto mb-3">
                                            @if($user->avatar)
                                                <img
                                                    id="avatarImg"
                                                    src="{{ asset('storage/avatars/' . $user->avatar) }}"
                                                    class="avatar-img"
                                                    alt="Avatar">
                                            @else
                                                <div id="avatarFallback" class="avatar-fallback">
                                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-2 fw-bold">Foto Profil</div>
                                        <div class="text-muted small mb-3">
                                            JPG / PNG / GIF • Maks 2MB
                                        </div>

                                        <input
                                            type="file"
                                            class="form-control file-input @error('avatar') is-invalid @enderror"
                                            id="avatar"
                                            name="avatar"
                                            accept="image/*"
                                        >

                                        @error('avatar')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-3 small text-muted">
                                            Tips: pakai foto terang biar terlihat aesthetic 💗
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT: Form -->
                            <div class="col-lg-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                        <input
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="name"
                                            name="name"
                                            value="{{ old('name', $user->name) }}"
                                            required
                                        >
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">Email</label>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email', $user->email) }}"
                                            required
                                        >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                        <input
                                            type="tel"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone', $user->phone) }}"
                                            placeholder="contoh: 08xxxxxxxxxx"
                                        >
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birthdate" class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input
                                            type="date"
                                            class="form-control @error('birthdate') is-invalid @enderror"
                                            id="birthdate"
                                            name="birthdate"
                                            value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}"
                                        >
                                        @error('birthdate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label fw-semibold">Jenis Kelamin</label>
                                        <select
                                            class="form-select @error('gender') is-invalid @enderror"
                                            id="gender"
                                            name="gender"
                                        >
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Status</label>
                                        <div class="status-box">
                                            @if($user->isPremium())
                                                <span class="status-pill premium">
                                                    <i class="fas fa-crown me-2"></i>Premium
                                                </span>
                                                <span class="text-muted small ms-2">
                                                    aktif sampai {{ optional($user->premium_until)->format('d F Y') }}
                                                </span>
                                            @else
                                                <span class="status-pill free">
                                                    <i class="fas fa-heart me-2"></i>Free
                                                </span>
                                                <span class="text-muted small ms-2">
                                                    upgrade untuk resep eksklusif
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold">Alamat</label>
                                        <textarea
                                            class="form-control @error('address') is-invalid @enderror"
                                            id="address"
                                            name="address"
                                            rows="4"
                                            placeholder="Tulis alamat kamu (opsional)"
                                        >{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="divider my-4"></div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('profile.show') }}" class="btn btn-outline-pink">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-gradient">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Small tip card -->
            <div class="tip-card mt-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="tip-icon"><i class="fas fa-lightbulb"></i></div>
                    <div>
                        <div class="fw-bold mb-1">Tips biar rapi</div>
                        <div class="text-muted">
                            Kalau upload avatar, pastiin ukurannya kotak (1:1) ya biar ga gepeng 💗
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
:root{
    --pink:#FF6B8B;
    --pink2:#C8A2C8;
    --pink-soft:#FFF0F5;
    --text:#1f2937;
}

/* header */
.edit-header{
    background: linear-gradient(135deg, rgba(255,107,139,.12), rgba(200,162,200,.12));
    border: 1px solid rgba(255,107,139,.15);
    border-radius: 18px;
    padding: 18px;
}

/* card */
.card-soft{
    background: rgba(255,255,255,.92);
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(255,107,139,.08);
    backdrop-filter: blur(6px);
}

/* buttons */
.btn-outline-pink{
    border: 2px solid var(--pink);
    color: var(--pink);
    border-radius: 12px;
    padding: 10px 16px;
    font-weight: 700;
    background: transparent;
}
.btn-outline-pink:hover{
    background: var(--pink);
    color:#fff;
}

.btn-gradient{
    background: linear-gradient(135deg, var(--pink), var(--pink2));
    border: none;
    color:#fff;
    border-radius: 14px;
    padding: 10px 16px;
    font-weight: 900;
    box-shadow: 0 12px 25px rgba(255,107,139,.18);
}
.btn-gradient:hover{
    filter: brightness(.98);
    color:#fff;
}

/* avatar area */
.avatar-card{
    border-radius: 18px;
    padding: 18px;
    background: rgba(255,107,139,.05);
    border: 1px solid rgba(255,107,139,.14);
}

.avatar-wrap{
    width: 130px;
    height: 130px;
    border-radius: 999px;
    padding: 4px;
    background: linear-gradient(135deg, var(--pink), var(--pink2));
}
.avatar-img{
    width:100%;
    height:100%;
    border-radius: 999px;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.9);
}
.avatar-fallback{
    width:100%;
    height:100%;
    border-radius: 999px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size: 54px;
    font-weight: 900;
    color:#fff;
    background: radial-gradient(circle at 30% 20%, rgba(255,255,255,.25), transparent 45%),
                linear-gradient(135deg, rgba(255,107,139,.95), rgba(200,162,200,.95));
    border: 3px solid rgba(255,255,255,.9);
}

.file-input{
    border-radius: 12px;
}

/* forms */
.form-control, .form-select{
    border-radius: 12px;
    padding: 10px 12px;
    border-color: rgba(0,0,0,.08);
}
.form-control:focus, .form-select:focus{
    border-color: rgba(255,107,139,.55);
    box-shadow: 0 0 0 .2rem rgba(255,107,139,.15);
}

.divider{
    height: 1px;
    background: rgba(255,107,139,.18);
}

.status-box{
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid rgba(255,107,139,.12);
    background: rgba(255,255,255,.86);
}
.status-pill{
    display:inline-flex;
    align-items:center;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 900;
}
.status-pill.premium{
    background: rgba(255,107,139,.12);
    color: var(--pink);
    border: 1px solid rgba(255,107,139,.25);
}
.status-pill.free{
    background: rgba(107,114,128,.10);
    color: #374151;
    border: 1px solid rgba(107,114,128,.18);
}

/* tip */
.tip-card{
    border-radius: 18px;
    padding: 18px;
    background: linear-gradient(135deg, rgba(255,107,139,.10), rgba(200,162,200,.10));
    border: 1px solid rgba(255,107,139,.14);
}
.tip-icon{
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(255,107,139,.18);
    color: var(--pink);
}

@media (max-width: 992px){
    .avatar-wrap{ width: 120px; height: 120px; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatar');

    input?.addEventListener('change', function (e) {
        const file = e.target.files?.[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (ev) {
            const wrap = document.querySelector('.avatar-wrap');
            if (!wrap) return;

            // remove fallback if exists
            const fallback = document.getElementById('avatarFallback');
            if (fallback) fallback.remove();

            // if image exists, update src; else create
            let img = document.getElementById('avatarImg');
            if (!img) {
                img = document.createElement('img');
                img.id = 'avatarImg';
                img.className = 'avatar-img';
                img.alt = 'Avatar Preview';
                wrap.appendChild(img);
            }
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
