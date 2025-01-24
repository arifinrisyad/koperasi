@extends('layouts.master')

@section('content')
<style>
    .profile-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
    }

    .profile-header h1 {
        color: #2c3e50;
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
        display: inline-block;
    }

    .profile-header h1:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: linear-gradient(to right, #3498db, #2ecc71);
        border-radius: 3px;
    }

    .profile-content {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }

    .profile-sidebar {
        flex: 0 0 300px;
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
        position: sticky;
        top: 20px;
    }

    .profile-image-container {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 0 auto 25px;
    }

    .profile-image {
        width: 200px;
        height: 200px;
        border-radius: 20px;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .profile-image:hover {
        transform: scale(1.02);
    }

    .profile-image-upload {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #3498db;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    }

    .profile-image-upload:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .profile-image-upload i {
        color: white;
        font-size: 18px;
    }

    .profile-info {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f5f6fa;
    }

    .profile-info h3 {
        color: #2c3e50;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .profile-info p {
        color: #7f8c8d;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .profile-info .role-badge {
        display: inline-block;
        padding: 6px 15px;
        background: linear-gradient(45deg, #3498db, #2ecc71);
        color: white;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-top: 10px;
    }

    .profile-form-container {
        flex: 1;
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 35px;
        background: #f8fafc;
        padding: 25px;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
    }

    .form-section-title {
        color: #2c3e50;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
    }

    .form-section-title i {
        margin-right: 10px;
        color: #3498db;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .form-control {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: white;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #a0aec0;
    }

    .btn-update {
        background: linear-gradient(45deg, #3498db, #2ecc71);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
    }

    .btn-update:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            120deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transition: all 0.6s;
    }

    .btn-update:hover:before {
        left: 100%;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(46, 204, 113, 0.2);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: none;
        display: flex;
        align-items: center;
    }

    .alert i {
        margin-right: 10px;
        font-size: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        box-shadow: 0 4px 15px rgba(21, 87, 36, 0.1);
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        box-shadow: 0 4px 15px rgba(114, 28, 36, 0.1);
    }

    .invalid-feedback {
        color: #e74c3c;
        font-size: 14px;
        margin-top: 8px;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .invalid-feedback i {
        margin-right: 6px;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 992px) {
        .profile-content {
            flex-direction: column;
        }

        .profile-sidebar {
            position: relative;
            top: 0;
            width: 100%;
            margin-bottom: 30px;
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 0 15px;
        }

        .profile-form-container {
            padding: 30px 20px;
        }

        .profile-header h1 {
            font-size: 28px;
        }

        .form-section {
            padding: 20px;
        }
    }
</style>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<div class="profile-container">
    <div class="profile-header">
        <h1> Profil</h1>
    </div>

                    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

    <div class="profile-content">
        <div class="profile-sidebar">
            <div class="profile-image-container">
                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/default-profile.png') }}" 
                     alt="Profile Photo" 
                     class="profile-image" 
                     id="previewImage">
                <label for="profile_photo" class="profile-image-upload">
                    <i class="fas fa-camera"></i>
                </label>
                                    <input type="file" 
                       id="profile_photo" 
                       name="profile_photo" 
                       accept="image/*" 
                       style="display: none"
                       onchange="previewFile()">
                                        </div>
            <div class="profile-info">
                <h3>{{ $user->name }}</h3>
                <p>{{ $user->email }}</p>
                <div class="role-badge">
                    {{ ucfirst($user->role) }}
                                </div>
                            </div>
                        </div>

        <div class="profile-form-container">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-user"></i>
                        Informasi Dasar
                    </h3>
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        </div>

                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-lock"></i>
                        Keamanan
                    </h3>
                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru (opsional)</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                            @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                            @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               placeholder="Konfirmasi password baru">
                    </div>
                </div>

                <button type="submit" class="btn-update">
                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    function previewFile() {
        const preview = document.getElementById('previewImage');
        const file = document.getElementById('profile_photo').files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    function updateProfileSidebar(name, email, role) {
        document.querySelector('.profile-info h3').textContent = name;
        document.querySelector('.profile-info p').textContent = email;
        document.querySelector('.profile-info .role-badge').textContent = role;
    }

    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const fileInput = document.getElementById('profile_photo');
        
        if (fileInput.files.length > 0) {
            formData.append('profile_photo', fileInput.files[0]);
        }

        formData.append('_method', 'PUT');

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan perubahan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (!response.ok) {
                        if (contentType && contentType.includes('application/json')) {
                            return response.json().then(json => {
                                throw new Error(json.message || 'Terjadi kesalahan');
                            });
                        }
                        throw new Error('Terjadi kesalahan pada server');
                    }
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    }
                    throw new Error('Response tidak valid');
                })
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        updateProfileSidebar(
                            data.user.name,
                            data.user.email,
                            data.user.role
                        );

                        if (data.user.profile_photo) {
                            document.getElementById('previewImage').src = data.user.profile_photo;
                            const navbarProfileImg = document.querySelector('.img-profile');
                            if (navbarProfileImg) {
                                navbarProfileImg.src = data.user.profile_photo;
                            }
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message || 'Terjadi kesalahan saat memperbarui profil',
                        showConfirmButton: true
                    });
                });
            }
        });
    });
</script>
@endsection
