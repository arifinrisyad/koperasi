// Konfigurasi SweetAlert2 untuk notifikasi tengah
const SwalCenter = Swal.mixin({
    position: 'center',
    showConfirmButton: true,
    confirmButtonText: 'OK',
    confirmButtonColor: '#4e73df',
    showClass: {
        popup: 'animate__animated animate__fadeIn'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOut'
    },
    customClass: {
        popup: 'center-popup'
    },
    width: '32em',
    padding: '2em',
    background: '#ffffff',
    backdrop: `
        rgba(0,0,0,0.4)
        left top
        no-repeat
    `
});

// Add custom styles
const style = document.createElement('style');
style.textContent = `
    .center-popup {
        border-radius: 15px !important;
        box-shadow: 0 0 30px rgba(0,0,0,0.2) !important;
    }

    .center-popup .swal2-title {
        font-size: 1.5em !important;
        font-weight: 600 !important;
        color: #333333 !important;
        margin: 0.5em 0 !important;
    }

    .center-popup .swal2-html-container {
        font-size: 1.1em !important;
        color: #666666 !important;
        margin-top: 0.5em !important;
    }

    .center-popup .swal2-icon {
        width: 5em !important;
        height: 5em !important;
        margin: 0.5em auto !important;
    }

    .center-popup .swal2-confirm {
        padding: 0.6em 2em !important;
        font-size: 1.1em !important;
        font-weight: 500 !important;
        border-radius: 10px !important;
    }
`;
document.head.appendChild(style);

// Function untuk menampilkan notifikasi sukses
function showCenterSuccess(message) {
    SwalCenter.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        timer: 3000,
        timerProgressBar: true
    });
}

// Function untuk menampilkan notifikasi error
function showCenterError(message) {
    SwalCenter.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        confirmButtonColor: '#e74a3b'
    });
}

// Function untuk konfirmasi hapus
function showDeleteConfirmation(id) {
    SwalCenter.fire({
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

// Initialize notifications
document.addEventListener('DOMContentLoaded', function() {
    // Success notification
    if (window.flashSuccess) {
        showCenterSuccess(window.flashSuccess);
    }
    
    // Error notification
    if (window.flashError) {
        showCenterError(window.flashError);
    }
});
