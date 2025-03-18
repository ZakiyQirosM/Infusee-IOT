@extends('layouts.main')

@section('title', 'Register')

@section('content')

<div class="register-container">
    <h2 class="register-title">Registrasi Infus</h2>

    {{-- Tampilkan pesan sukses jika ada --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tampilkan pesan error jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    {{-- Form Registrasi --}}
    <form id="register-form" action="{{ route('register.store') }}" method="POST">
        @csrf

        {{-- No Register --}}
        <div class="form-group">
            <label class="register-label">No Register</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" id="no_reg_pasien" name="no_reg_pasien" class="register-input" placeholder="Masukkan Nomor Registrasi Pasien" required>
            </div>
            <button type="button" id="btn-search" class="register-btn-search">Cari</button>
        </div>

        {{-- Nama Pasien --}}
        <div class="form-group">
            <label class="register-label">Nama Pasien</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" id="nama_pasien" name="nama_pasien" class="register-input register-input-disabled" placeholder="Nama Pasien" disabled>
            </div>
        </div>

        {{-- Umur --}}
        <div class="form-group">
            <label class="register-label">Umur</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="number" id="umur" name="umur" class="register-input register-input-disabled" placeholder="Umur" disabled>
            </div>
        </div>

        {{-- No Ruangan --}}
        <div class="form-group">
            <label class="register-label">No Ruangan</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" id="no_ruangan" name="no_ruangan" class="register-input register-input-disabled" placeholder="Nomor Ruangan" disabled>
            </div>
        </div>

        {{-- Durasi --}}
        <div class="form-group">
            <label class="register-label">Durasi (menit)</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="number" id="durasi" name="durasi" class="register-input" value="0" required>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <button type="submit" class="register-btn-submit">Simpan Data</button>
    </form>
</div>

{{-- Style --}}
<style>
    /* Container form */
    .register-container {
        background-color: #ffffff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-top: 20px;
        width: 100%;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Judul */
    .register-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 16px;
        text-align: center;
        color: #333;
    }

    /* Group Form */
    .form-group {
        margin-bottom: 16px;
        display: flex;
        align-items: center;
    }

    /* Label form */
    .register-label {
        width: 25%;
        font-weight: 500;
        color: #333;
        padding-right: 10px;
    }

    /* Input container */
    .register-input-container {
        display: flex;
        align-items: center;
        width: 60%;
    }

    .register-input-container span {
        margin-right: 5px;
        font-weight: 500;
        color: #555;
    }

    /* Input */
    .register-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: border-color 0.2s ease;
        font-size: 16px;
        color: #333;
    }

    .register-input:focus {
        outline: none;
        border-color: #00C7B4;
        box-shadow: 0 0 5px rgba(0, 199, 180, 0.5);
    }

    /* Input disabled */
    .register-input-disabled {
        background-color: #f3f3f3;
        color: #999;
    }

    /* Tombol Cari */
    .register-btn-search {
        background-color: #00C7B4;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
        margin-left: 10px;
    }

    .register-btn-search:hover {
        background-color: #009688;
    }

    /* Tombol Submit */
    .register-btn-submit {
        background-color: #00C7B4;
        color: #fff;
        padding: 14px;
        width: 100%;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
        margin-top: 20px;
    }

    .register-btn-submit:hover {
        background-color: #009688;
    }
</style>

@endsection

@section('scripts')
{{-- jQuery untuk AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Klik tombol "Cari"
        $('#btn-search').on('click', function () {
            let noRegister = $('#no_reg_pasien').val().trim();

            if (noRegister !== '') {
                $.ajax({
                    url: "{{ route('register.search') }}", // Route ke controller
                    type: 'GET',
                    data: { 
                        _token: '{{ csrf_token() }}', // Kirim CSRF token
                        no_reg_pasien: noRegister 
                    },
                    success: function (response) {
                        $('#nama_pasien').val(response.nama_pasien);
                        $('#umur').val(response.umur);
                        $('#no_ruangan').val(response.no_ruangan);
                        $('.register-input-disabled').prop('disabled', false);
                    },
                    error: function (xhr) {
                        let errorMessage = xhr.responseJSON.error || 'Terjadi kesalahan pada server.';
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
</script>
@endsection

