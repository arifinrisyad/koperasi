// Konfigurasi SweetAlert2 untuk notifikasi
const Toast = Swal.mixin({
    toast: false,
    position: 'center',
    showConfirmButton: true,
    confirmButtonText: 'OK',
    timer: 3000,
    timerProgressBar: true,
    showClass: {
        popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
    },
    customClass: {
        popup: 'notification-popup'
    },
    width: '32em',
    padding: '1.5em',
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
    .notification-popup {
        border-radius: 15px !important;
        box-shadow: 0 0 30px rgba(0,0,0,0.2) !important;
    }

    .notification-popup .swal2-title {
        font-size: 1.5em !important;
        font-weight: 600 !important;
        color: #333333 !important;
        margin: 0.5em 0 !important;
    }

    .notification-popup .swal2-html-container {
        font-size: 1.1em !important;
        color: #666666 !important;
        margin-top: 0.5em !important;
    }

    .notification-popup .swal2-icon {
        width: 5em !important;
        height: 5em !important;
        margin: 1em auto 0.5em auto !important;
    }

    .notification-popup .swal2-confirm {
        padding: 0.6em 2em !important;
        font-size: 1.1em !important;
        font-weight: 500 !important;
        border-radius: 10px !important;
    }

    .notification-popup .swal2-success {
        border-color: #a5dc86 !important;
    }

    .notification-popup .swal2-success [class^=swal2-success-line] {
        background-color: #a5dc86 !important;
    }

    .notification-popup .swal2-success-ring {
        border: 0.25em solid rgba(165, 220, 134, 0.3) !important;
    }
`;
document.head.appendChild(style);

// Function untuk menampilkan notifikasi sukses
function showSuccessNotification(message) {
    Toast.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        confirmButtonColor: '#4e73df'
    });
}

// Function untuk menampilkan notifikasi error
function showErrorNotification(message) {
    Toast.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        confirmButtonColor: '#e74a3b',
        timer: null
    });
}

// Inisialisasi notifikasi dari session
document.addEventListener('DOMContentLoaded', function() {
    // Notifikasi sukses
    const successMessage = document.querySelector('div[data-success-message]');
    if (successMessage) {
        showSuccessNotification(successMessage.getAttribute('data-success-message'));
    }

    // Notifikasi error
    const errorMessage = document.querySelector('div[data-error-message]');
    if (errorMessage) {
        showErrorNotification(errorMessage.getAttribute('data-error-message'));
    }
});
