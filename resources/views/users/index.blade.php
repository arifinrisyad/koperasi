@extends('layouts.master')

<style>
    /* Custom Styles */
    .page-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .page-header h1 {
        color: white;
        margin: 0;
        font-size: 1.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .page-header h1 i {
        margin-right: 0.75rem;
        font-size: 2rem;
    }

    .add-user-btn {
        background: white;
        color: #4e73df;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .add-user-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        background: #4e73df;
        color: white;
    }

    .search-box {
        max-width: 300px;
        margin-left: auto;
    }

    .search-box .input-group {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .search-box input {
        border: none;
        padding: 0.75rem 1rem;
    }

    .search-box .btn {
        padding: 0.75rem 1.25rem;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        background: #f8f9fc;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-group .btn {
        border-radius: 6px !important;
        margin: 0 0.25rem;
        padding: 0.5rem 1rem;
    }

    .status-btn {
        min-width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .online-status {
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }

    .pagination {
        margin: 0;
    }

    .pagination .page-link {
        border: none;
        padding: 0.75rem 1rem;
        margin: 0 0.25rem;
        border-radius: 6px;
        color: #4e73df;
        font-weight: 500;
    }

    .pagination .page-item.active .page-link {
        background: #4e73df;
        color: white;
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
    }

    .empty-state img {
        width: 150px;
        height: 150px;
        opacity: 0.6;
        margin-bottom: 1.5rem;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: 15px;
    }

    .modal-header {
        background: #4e73df;
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid #e9ecef;
    }
</style>

@section('content')
<div class="container-fluid">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-users"></i> Manajemen Pengguna</h1>
            <button class="add-user-btn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus"></i>
                Tambah Pengguna
            </button>
        </div>
    </div>

    <!-- Content Card -->
    <div class="card shadow">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list mr-1"></i>
                    Daftar Pengguna
                </h6>
                <div class="search-box">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari nama pengguna...">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar bg-light">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-primary">Administrator</span>
                                        @break
                                    @case('petugas')
                                        <span class="badge bg-info">Petugas</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $user->role }}</span>
                                @endswitch
                            </td>
                            <td class="text-center">
                                @if($user->role === 'petugas')
                                <div class="d-flex flex-column align-items-center">
                                    <button 
                                        class="btn btn-sm status-btn {{ $user->is_active ? 'btn-success' : 'btn-danger' }} mb-1"
                                        onclick="toggleStatus({{ $user->id }}, this)"
                                        data-status="{{ $user->is_active }}"
                                    >
                                        <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                    <span class="badge {{ $user->isOnline() ? 'bg-success' : 'bg-secondary' }} online-status" 
                                          data-user-id="{{ $user->id }}">
                                        <i class="fas fa-{{ $user->isOnline() ? 'signal' : 'power-off' }}"></i>
                                        {{ $user->isOnline() ? 'Online' : 'Offline' }}
                                    </span>
                                    @if(!$user->isOnline() && $user->last_seen)
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $user->last_seen->diffForHumans() }}
                                        </small>
                                    @endif
                                </div>
                                @else
                                <button 
                                    class="btn btn-sm status-btn {{ $user->is_active ? 'btn-success' : 'btn-danger' }}"
                                    onclick="toggleStatus({{ $user->id }}, this)"
                                    data-status="{{ $user->is_active }}"
                                >
                                    <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('users.show', $user->id) }}" 
                                       class="btn btn-info btn-sm"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <button type="button" 
                                            class="btn btn-danger btn-sm delete-user"
                                            data-id="{{ $user->id }}"
                                            data-url="{{ route('users.destroy', $user->id) }}"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7465/7465679.png" 
                                         alt="No Data">
                                    <p>Belum ada data pengguna</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center p-3">
                <div class="text-muted">
                    @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                    @else
                        Total: {{ $users->count() }} data
                    @endif
                </div>
                <div>
                    @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $users->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus"></i>
                    Tambah Pengguna Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Administrator</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($users as $user)
<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.update', $user->id) }}" method="POST" id="editForm{{ $user->id }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach

@push('scripts')
<script>
$(document).ready(function() {
    // Setup AJAX CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle add user form submission
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var modal = $('#addUserModal');
        var submitBtn = form.find('button[type="submit"]');
        
        // Disable submit button and show loading state
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Hide modal and reset form
                modal.modal('hide');
                form[0].reset();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pengguna baru berhasil ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                });

                // Reload the page after success
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menambah pengguna';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    showConfirmButton: true
                });
            },
            complete: function() {
                // Reset submit button
                submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            }
        });
    });

    // Handle delete user
    $('.delete-user').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var userId = button.data('id');
        var deleteUrl = button.data('url');
        var row = button.closest('tr');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pengguna akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        '_method': 'DELETE'
                    },
                    success: function(response) {
                        // Remove the row with animation
                        row.fadeOut(400, function() {
                            row.remove();
                            
                            // Update the counter
                            var total = $('#usersTable tbody tr').length;
                            $('.text-muted').text('Total: ' + total + ' data');
                            
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pengguna berhasil dihapus',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        });
                    },
                    error: function(xhr) {
                        var errorMessage = 'Terjadi kesalahan saat menghapus pengguna';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            showConfirmButton: true
                        });
                    }
                });
            }
        });
    });

    // Function to update counter
    function updateCounter() {
        var total = $('#usersTable tbody tr').length;
        $('.text-muted').text(`Total: ${total} data`);
    }

    // Search functionality
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#usersTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Success and error notifications
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            showConfirmButton: true
        });
    @endif
});

// Toggle user status function
function toggleStatus(userId, button) {
    var currentStatus = $(button).data('status');
    var newStatus = !currentStatus;
    
    Swal.fire({
        title: 'Konfirmasi',
        text: `Apakah Anda yakin ingin ${newStatus ? 'mengaktifkan' : 'menonaktifkan'} pengguna ini?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: newStatus ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: `/users/${userId}/toggle-status`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            }).catch(error => {
                Swal.showValidationMessage(
                    'Terjadi kesalahan: ' + (error.responseJSON?.message || 'Tidak dapat mengubah status')
                );
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Update button appearance
            $(button)
                .data('status', newStatus)
                .removeClass(newStatus ? 'btn-danger' : 'btn-success')
                .addClass(newStatus ? 'btn-success' : 'btn-danger')
                .html(`<i class="fas fa-${newStatus ? 'check' : 'times'}-circle"></i> ${newStatus ? 'Aktif' : 'Nonaktif'}`);
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `Status pengguna berhasil ${newStatus ? 'diaktifkan' : 'dinonaktifkan'}`,
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}
</script>
@endpush
@endsection
