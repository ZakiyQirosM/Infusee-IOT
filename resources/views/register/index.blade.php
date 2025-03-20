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

    {{-- Form Pencarian Data Pasien --}}
    <form id="search-form" action="{{ route('register.search') }}" method="GET">
        <div class="form-group">
            <label class="register-label">No Register</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" id="no_reg_pasien" name="no_reg_pasien" class="register-input" 
                    placeholder="Masukkan Nomor Registrasi Pasien" value="{{ request('no_reg_pasien') ?? old('no_reg_pasien') }}" required>
            </div>
            <button type="submit" class="register-btn-search">Cari</button>
        </div>
    </form>

    {{-- Form Registrasi --}}
    <form id="register-form" action="{{ route('register.store') }}" method="POST">
        @csrf

        {{-- Nama Pasien --}}
        <div class="form-group">
            <label class="register-label">Nama Pasien</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" id="nama_pasien" name="nama_pasien" class="register-input register-input-disabled"
                    placeholder="Nama Pasien" value="{{ $nama_pasien ?? old('nama_pasien') }}" readonly>
            </div>
        </div>

        {{-- Umur --}}
        <div class="form-group">
            <label class="register-label">Umur</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="number" id="umur" name="umur" class="register-input register-input-disabled"
                    placeholder="Umur" value="{{ $umur ?? old('umur') }}" readonly>
            </div>
        </div>

        {{-- No Ruangan --}}
        <div class="form-group">
            <label class="register-label">No Ruangan</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="text" id="no_ruangan" name="no_ruangan" class="register-input register-input-disabled"
                    placeholder="Nomor Ruangan" value="{{ $no_ruangan ?? old('no_ruangan') }}" readonly>
            </div>
        </div>

        {{-- Durasi --}}
        <div class="form-group">
            <label class="register-label">Durasi (menit)</label>
            <div class="register-input-container">
                <span>:</span>
                <input type="number" id="durasi" name="durasi" class="register-input" value="1" min="1" required>
            </div>
        </div>

        {{-- Tombol Submit --}}
        <button type="submit" class="register-btn-submit">Simpan Data</button>
    </form>
</div>

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

@endsection

