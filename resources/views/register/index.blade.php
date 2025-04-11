@extends('layouts.main')

@section('title', 'Register')

@section('content')

<div class='register-container'>



    <form id="register-form" action="{{ route('register.store') }}" method="POST">
        @csrf
        <h2 class="register-title">
            Registrasi Infusee
        </h4>

        <div class="divider-reg"></div>

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
                <input type="text" id="nama_pasien" name="nama_pasien" class="register-input" placeholder="Nama Pasien" disabled>
            </div>
        </div>

        {{-- Umur --}}
        <div class="form-group">
            <label class="register-label">Umur</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="number" id="umur" name="umur" class="register-input" placeholder="Umur" disabled>
            </div>
        </div>

        {{-- No Ruangan --}}
        <div class="form-group">
            <label class="register-label">No Ruangan</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" id="no_ruangan" name="no_ruangan" class="register-input" placeholder="Nomor Ruangan" disabled>
            </div>
        </div>

        {{-- Durasi --}}
        <div class="form-group">
            <label class="register-label">Durasi (jam)</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="number" id="durasi" name="durasi" class="register-input" value="0" required>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <button type="submit" class="register-btn-submit">Simpan Data</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ✅ Validasi form
        const form = document.getElementById('register-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const durasiInput = document.getElementById('durasi');
            const durasi = parseInt(durasiInput.value);

            if (isNaN(durasi) || durasi <= 1) {
                showAlert('Durasi tidak boleh kurang dari 1 menit!');
                durasiInput.focus();
                return;
            }

            fetch("{{ route('register.store') }}", {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    showAlert('Data berhasil disimpan!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('devices.index') }}";
                    }, 1500);
                } else {
                    showAlert('Gagal menyimpan data!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan server!');
            });
        });
    });
</script>
@endpush
