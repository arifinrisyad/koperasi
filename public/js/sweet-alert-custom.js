// Konfigurasi default untuk SweetAlert2
const SweetAlertConfig = {
    position: 'center', // Posisi di tengah
    backdrop: true,     // Menampilkan backdrop
    allowOutsideClick: false, // Mencegah klik di luar popup
    showConfirmButton: true,
    confirmButtonText: 'OK',
    confirmButtonColor: '#4e73df',
    customClass: {
        popup: 'animated fadeIn'
    },
    showClass: {
        popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
    }
};

// Function untuk menampilkan notifikasi sukses
function showSuccess(message) {
    Swal.fire({
        ...SweetAlertConfig,
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        timer: 2000,
        timerProgressBar: true
    });
}

// Function untuk menampilkan notifikasi error
function showError(message) {
    Swal.fire({
        ...SweetAlertConfig,
        icon: 'error',
        title: 'Error!',
        text: message,
        confirmButtonColor: '#e74a3b'
    });
}

// Function untuk konfirmasi hapus
function confirmDelete(id) {
    Swal.fire({
        ...SweetAlertConfig,
        title: 'Apakah Anda yakin?',
        text: "Data laporan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

// Tambahkan style kustom
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .swal2-popup {
            border-radius: 15px !important;
            padding: 2em !important;
        }
        
        .swal2-title {
            font-size: 1.5em !important;
            font-weight: 600 !important;
            color: #333 !important;
        }
        
        .swal2-html-container {
            font-size: 1.1em !important;
            color: #666 !important;
        }
        
        .swal2-icon {
            width: 5em !important;
            height: 5em !important;
            margin: 1em auto !important;
        }
        
        .swal2-confirm, .swal2-cancel {
            padding: 0.6em 2em !important;
            font-size: 1.1em !important;
            font-weight: 500 !important;
            border-radius: 8px !important;
        }
        
        .swal2-backdrop-show {
            background: rgba(0,0,0,0.4) !important;
        }
    </style>
`);

// Inisialisasi notifikasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Cek session success
    if (typeof successMessage !== 'undefined') {
        showSuccess(successMessage);
    }
    
    // Cek session error
    if (typeof errorMessage !== 'undefined') {
        showError(errorMessage);
    }
});
