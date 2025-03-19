$(document).ready(function () {
    // Klik tombol "Cari"
    $('#btn-search').on('click', function () {
        let noRegister = $('#no_reg_pasien').val().trim();

        if (noRegister !== '') {
            $.ajax({
                url: '/register/search', // Sesuaikan jika menggunakan named route Laravel
                type: 'GET',
                data: { no_reg_pasien: noRegister }, // Hanya kirim data yang diperlukan
                success: function (response) {
                    $('#nama_pasien').val(response.nama_pasien);
                    $('#umur').val(response.umur);
                    $('#no_ruangan').val(response.no_ruangan);
                    $('.register-input-disabled').prop('disabled', false);
                },
                error: function (xhr) {
                    let errorMessage = xhr.responseJSON?.error || 'Terjadi kesalahan pada server.';
                    $('.alert').remove(); // Hapus pesan error lama
                    $('<div class="alert alert-danger">' + errorMessage + '</div>').insertBefore('#register-form');
                }
            });
        } else {
            $('.alert').remove(); // Hapus pesan error lama
            $('<div class="alert alert-danger">Masukkan No Register terlebih dahulu.</div>').insertBefore('#register-form');
        }
    });
});