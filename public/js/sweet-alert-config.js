// Konfigurasi SweetAlert2
const SwalConfig = Swal.mixin({
    position: 'center',
    showConfirmButton: true,
    confirmButtonText: 'OK',
    confirmButtonColor: '#4e73df',
    customClass: {
        popup: 'animated bounceIn',
        title: 'text-dark',
        content: 'text-secondary'
    },
    showClass: {
        popup: 'animate__animated animate__fadeIn'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOut'
    },
    padding: '2em',
    width: '32em',
    background: '#ffffff',
    backdrop: `
        rgba(0,0,0,0.4)
        left top
        no-repeat
    `
});

// Success Alert
function showSuccessAlert(message) {
    SwalConfig.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        timer: 3000,
        timerProgressBar: true
    });
}

// Error Alert
function showErrorAlert(message) {
    SwalConfig.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        confirmButtonColor: '#e74a3b'
    });
}

// Warning Alert
function showWarningAlert(message) {
    SwalConfig.fire({
        icon: 'warning',
        title: 'Peringatan!',
        text: message,
        confirmButtonColor: '#f6c23e'
    });
}

// Delete Confirmation
function showDeleteConfirmation(id) {
    SwalConfig.fire({
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

// Add custom styles
const style = document.createElement('style');
style.textContent = `
    .swal2-popup {
        border-radius: 15px !important;
        box-shadow: 0 0 30px rgba(0,0,0,0.2) !important;
    }
    
    .swal2-title {
        font-size: 1.8em !important;
        font-weight: 600 !important;
        color: #333333 !important;
        margin: 0.5em 0 !important;
    }
    
    .swal2-html-container {
        font-size: 1.1em !important;
        color: #666666 !important;
        margin-top: 0.5em !important;
    }
    
    .swal2-icon {
        width: 5em !important;
        height: 5em !important;
        margin: 1.5em auto 0.5em auto !important;
        border-width: 0.25em !important;
    }
    
    .swal2-icon-content {
        font-size: 3.75em !important;
    }
    
    .swal2-confirm, .swal2-cancel {
        padding: 0.6em 2em !important;
        font-size: 1.1em !important;
        font-weight: 500 !important;
        border-radius: 10px !important;
    }
    
    .swal2-success-circular-line-left,
    .swal2-success-circular-line-right,
    .swal2-success-fix {
        background-color: transparent !important;
    }
`;
document.head.appendChild(style);
