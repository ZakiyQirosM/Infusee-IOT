@extends('layouts.main')

@section('title', 'Register')

@section('content')

<div class='register-container'>
    <form id="register-form" action="{{ route('register.store') }}" method="POST">
        @csrf
        <h2 class="register-title">
            Registrasi Infusee
<<<<<<< HEAD
        </h2>

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
=======
        </h4>

>>>>>>> d7510f2 (add file migration, model, dan controler, serta cari data pasien bisa)
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
@endsection
<<<<<<< HEAD

<script>
    document.getElementById('register-form').addEventListener('submit', (e) => {
        e.preventDefault();

        fetch("{{ route('register.store') }}", {
            method: 'POST',
            body: new FormData(e.target),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.ok) {
                // ✅ Gunakan route Laravel sebagai path URL
                window.location.href = "{{ route('devices.index') }}";
            } else {
                alert('Gagal menyimpan data');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
=======
>>>>>>> d7510f2 (add file migration, model, dan controler, serta cari data pasien bisa)
