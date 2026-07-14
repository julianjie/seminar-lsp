import './bootstrap';

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Swal = Swal;

document.addEventListener('DOMContentLoaded', () => {
    /*
    |--------------------------------------------------------------------------
    | Konfirmasi form
    |--------------------------------------------------------------------------
    */
    document.querySelectorAll('.swal-confirm-form').forEach((form) => {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const result = await Swal.fire({
                icon: this.dataset.icon || 'warning',
                title: this.dataset.title || 'Apakah Anda yakin?',
                text: this.dataset.text || '',
                showCancelButton: true,
                confirmButtonText:
                    this.dataset.confirmText || 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor:
                    this.dataset.confirmColor || '#0d6efd',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
            });

            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Notifikasi berhasil dari session Laravel
    |--------------------------------------------------------------------------
    */
    const successMessage = document.getElementById('flash-success');

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: successMessage.dataset.message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#198754',
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Notifikasi gagal dari session Laravel
    |--------------------------------------------------------------------------
    */
    const errorMessage = document.getElementById('flash-error');

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: errorMessage.dataset.message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545',
        });
    }
});