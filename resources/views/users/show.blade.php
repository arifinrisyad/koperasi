@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detail Pengguna</h1>
            <p class="text-muted mb-0">Informasi lengkap tentang pengguna</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Kembali</span>
        </a>
    </div>

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle mr-1"></i>
                        Profil Pengguna
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle text-gray-300" style="font-size: 6rem;"></i>
                    </div>
                    <h4 class="font-weight-bold text-gray-800">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    
                    @switch($user->role)
                        @case('admin')
                            <span class="badge badge-danger px-3 py-2">Administrator</span>
                            @break
                        @case('petugas')
                            <span class="badge badge-warning px-3 py-2">Petugas</span>
                            @break
                        @default
                            <span class="badge badge-secondary px-3 py-2">{{ $user->role }}</span>
                    @endswitch

                    <div class="mt-4">
                        <div class="row">
                            <div class="col-6 border-right">
                                <div class="font-weight-bold mb-0">Status</div>
                                <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }} mt-2">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <div class="col-6">
                                <div class="font-weight-bold mb-0">Status Online</div>
                                <span class="badge {{ $user->isOnline() ? 'badge-success' : 'badge-secondary' }} mt-2">
                                    {{ $user->isOnline() ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details Card -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi Detail
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-calendar-plus bg-primary"></i>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold mb-1">Tanggal Bergabung</h6>
                                <p class="mb-0">{{ $user->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <i class="fas fa-clock bg-info"></i>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold mb-1">Terakhir Diperbarui</h6>
                                <p class="mb-0">{{ $user->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        @if(!$user->isOnline() && $user->last_seen)
                        <div class="timeline-item">
                            <i class="fas fa-history bg-warning"></i>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold mb-1">Terakhir Dilihat</h6>
                                <p class="mb-0">{{ $user->last_seen->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(auth()->user()->role === 'admin')
                    <div class="mt-4 text-center">
                        <button type="button" 
                                class="btn {{ $user->is_active ? 'btn-success' : 'btn-danger' }} btn-icon-split status-btn"
                                onclick="toggleStatus({{ $user->id }}, this)"
                                data-status="{{ $user->is_active }}">
                            <span class="icon text-white-50">
                                <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            </span>
                            <span class="text">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    margin-bottom: 30px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item i {
    position: absolute;
    left: 0;
    top: 0;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    text-align: center;
    line-height: 35px;
    color: #fff;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: 17px;
    top: 35px;
    height: calc(100% + 15px);
    width: 2px;
    background-color: #e3e6f0;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-content {
    padding: 0 15px;
}

.badge {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.35rem 0.5rem;
}

.badge-primary {
    background-color: #4e73df;
}

.badge-success {
    background-color: #1cc88a;
}

.badge-info {
    background-color: #36b9cc;
}

.badge-danger {
    background-color: #e74a3b;
}

.badge-secondary {
    background-color: #858796;
}

.badge-warning {
    background-color: #f6c23e;
}

.img-profile {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endpush

@push('scripts')
<script>
    function toggleStatus(userId, button) {
        var currentStatus = $(button).data('status');
        var newStatus = !currentStatus;
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Status pengguna akan diubah menjadi ' + (newStatus ? 'aktif' : 'nonaktif'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/' + userId + '/toggle-status',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button appearance
                            $(button).data('status', newStatus);
                            if (newStatus) {
                                $(button).removeClass('btn-danger').addClass('btn-success');
                                $(button).find('.icon i').removeClass('fa-times-circle').addClass('fa-check-circle');
                                $(button).find('.text').text('Aktif');
                            } else {
                                $(button).removeClass('btn-success').addClass('btn-danger');
                                $(button).find('.icon i').removeClass('fa-check-circle').addClass('fa-times-circle');
                                $(button).find('.text').text('Nonaktif');
                            }
                            
                            // Update status badges
                            $('.badge-status').replaceWith(
                                '<span class="badge ' + (newStatus ? 'badge-success' : 'badge-danger') + ' badge-status">' +
                                (newStatus ? 'Aktif' : 'Nonaktif') + '</span>'
                            );

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Status pengguna berhasil diperbarui',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat mengubah status pengguna',
                        });
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection
