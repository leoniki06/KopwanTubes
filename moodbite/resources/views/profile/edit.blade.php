@extends('layouts.app')

@section('title', 'Edit Profil - MoodBite')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-user-edit me-2" style="color: #FF6B8B;"></i>
                    Edit Profil
                </h5>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4 mb-3 text-center">
                            <!-- Avatar Preview -->
                            <div class="mb-3">
                                @if(Auth::user()->avatar)
                                    <img id="avatarPreview" src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" 
                                         class="rounded-circle" width="150" height="150" alt="Avatar">
                                @else
                                    <div id="avatarPreview" class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                         style="width: 150px; height: 150px; background: linear-gradient(135deg, #FF6B8B, #FFD166);">
                                        <span class="text-white display-4">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Ubah Foto Profil</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <small class="text-muted">Maksimal 2MB, format: JPG, PNG, GIF</small>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <!-- Form Fields -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="{{ old('phone', Auth::user()->phone) }}">
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="birthdate" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" 
                                           value="{{ old('birthdate', Auth::user()->birthdate ? Auth::user()->birthdate->format('Y-m-d') : '') }}">
                                    @error('birthdate')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male" {{ old('gender', Auth::user()->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('gender', Auth::user()->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                        <option value="other" {{ old('gender', Auth::user()->gender) == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', Auth::user()->address) }}</textarea>
                                    @error('address')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview avatar before upload
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                preview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" width="150" height="150" alt="Avatar Preview">`;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection