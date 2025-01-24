// Custom SweetAlert2 Notifications
const Toast = Swal.mixin({
    toast: false,
    position: 'center',
    showConfirmButton: true,
    confirmButtonColor: '#4e73df',
    timer: 3000,
    timerProgressBar: true,
    showClass: {
        popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
    },
    customClass: {
        popup: 'colored-toast'
    }
});

// Success Notification
function showSuccess(message) {
    Toast.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        background: '#ffffff',
        confirmButtonText: 'OK',
        padding: '1em',
        showClass: {
            popup: 'animate__animated animate__fadeIn'
        }
    });
}

// Error Notification
function showError(message) {
    Toast.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        background: '#ffffff',
        confirmButtonColor: '#e74a3b',
        confirmButtonText: 'OK',
        timer: null,
        padding: '1em',
        showClass: {
            popup: 'animate__animated animate__shakeX'
        }
    });
}

// Delete Confirmation
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data laporan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        padding: '2em',
        customClass: {
            popup: 'delete-popup',
            title: 'delete-title',
            content: 'delete-content',
            confirmButton: 'delete-confirm-btn',
            cancelButton: 'delete-cancel-btn'
        },
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

// Add custom styles
const style = document.createElement('style');
style.textContent = `
    .colored-toast {
        border-radius: 15px !important;
        box-shadow: 0 0 20px rgba(0,0,0,0.1) !important;
    }
    
    .colored-toast .swal2-title {
        color: #333333 !important;
        font-size: 1.5em !important;
    }
    
    .colored-toast .swal2-html-container {
        color: #666666 !important;
        font-size: 1.1em !important;
        margin-top: 0.5em !important;
    }
    
    .colored-toast .swal2-icon {
        margin: 1em auto 0.5em auto !important;
    }
    
    .delete-popup {
        border-radius: 20px !important;
        background: #ffffff !important;
        box-shadow: 0 0 30px rgba(0,0,0,0.15) !important;
    }
    
    .delete-title {
        color: #333333 !important;
        font-size: 1.8em !important;
        font-weight: 600 !important;
    }
    
    .delete-content {
        color: #666666 !important;
        font-size: 1.1em !important;
    }
    
    .delete-confirm-btn, .delete-cancel-btn {
        padding: 0.6em 2em !important;
        font-size: 1.1em !important;
        font-weight: 500 !important;
        border-radius: 10px !important;
    }
    
    .swal2-popup {
        width: 32em !important;
    }
    
    .swal2-icon {
        width: 5em !important;
        height: 5em !important;
        margin: 1.5em auto 0.5em auto !important;
    }
    
    .swal2-icon .swal2-icon-content {
        font-size: 3.75em !important;
    }
`;
document.head.appendChild(style);
