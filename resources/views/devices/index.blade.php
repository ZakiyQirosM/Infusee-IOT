@extends('layouts.main')

@section('title', 'Pilih Device')

@section('content')
<<<<<<< HEAD
<div class="device-container">
    <h2>Pilih Device untuk Pasien</h2>

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> ca26df1 (regis sudah bisa konek ke device)
    {{-- Jika tidak ada device --}}
    @if ($devices->isEmpty())
        <p class="no-device">Tidak ada device aktif.</p>
    @else
    <div class="device-list">
        @foreach ($devices as $device)
            <div class="device-card" data-device-id="{{ $device->id_perangkat_infusee }}">
                <div class="device-info">
                    <h3>ID: {{ $device->id_perangkat_infusee }}</h3>
                    <p>IP: {{ $device->alamat_ip_infusee }}</p>
                </div>
=======
<div class="container">
    <div class="row">
        {{-- ✅ Kolom kiri untuk data pasien --}}
        <div class="col-md-5">
            <div class="patient-info">
                <h2>Data Pasien</h2>
                <p><strong>No. Registrasi:</strong> {{ session('no_reg_pasien') }}</p>
                <p><strong>Nama:</strong> {{ session('nama_pasien') }}</p>
                <p><strong>Umur:</strong> {{ session('umur') }}</p>
                <p><strong>No. Ruangan:</strong> {{ session('no_ruangan') }}</p>
                <p><strong>Durasi Infus:</strong> {{ session('durasi_infus_menit') }} menit</p>
>>>>>>> a916abf (show data di list device  yang diinput di regis)
            </div>
        </div>

        {{-- ✅ Kolom kanan untuk list device --}}
        <div class="col-md-7">
            <h2>Pilih Device untuk Pasien</h2>

            @if ($devices->isEmpty())
                <p class="alert alert-warning">Tidak ada device aktif.</p>
            @else
                <div class="device-list">
                    @foreach ($devices as $device)
                        <div class="device-card">
                            <div class="device-info" onclick="selectDevice('{{ $device->id_perangkat_infusee }}')">
                                <h3>ID: {{ $device->id_perangkat_infusee }}</h3>
                                <p>IP: {{ $device->alamat_id_infusee }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
<<<<<<< HEAD
    @endif
<<<<<<< HEAD
=======
>>>>>>> a916abf (show data di list device  yang diinput di regis)
</div>

{{-- ✅ CSRF Token untuk keperluan POST request --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection
<<<<<<< HEAD

<script>
    function selectDevice(deviceId) {
        console.log(`Device ${deviceId} selected`);

        if (confirm(`Pilih device dengan ID: ${deviceId}?`)) {
            fetch("{{ route('devices.assign') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    device_id: deviceId
                })
            })
            .then(response => {
                if (response.ok) {
                    alert("Device berhasil dipilih!");
                    window.location.href = "{{ route('register.index') }}"; // ✅ Redirect ke halaman register
                } else {
                    return response.json().then(data => {
                        alert(`Gagal memilih device: ${data.error || 'Terjadi kesalahan.'}`);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Terjadi kesalahan saat memilih device.");
            });
        }
    }
</script>
=======
    {{-- Tabel daftar perangkat --}}
    <table class="device-table">
        <thead>
            <tr>
                <th>ID Perangkat</th>
                <th>Alamat IP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($devices as $device)
                <tr>
                    <td>{{ $device->id_perangkat_infusee }}</td>
                    <td>{{ $device->alamat_ip_infusee }}</td>
                    <td>
                        <form action="{{ route('devices.assign') }}" method="POST">
                            @csrf
                            <input type="hidden" name="device_id" value="{{ $device->id_perangkat_infusee }}">
                            <button type="submit" class="btn-pilih">Pilih</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
>>>>>>> d7510f2 (add file migration, model, dan controler, serta cari data pasien bisa)
=======
</div>
@endsection

<script>
    function selectDevice(deviceId) {
        console.log(`Device ${deviceId} selected`);

        if (confirm(`Pilih device dengan ID: ${deviceId}?`)) {
            fetch("{{ route('devices.assign') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    device_id: deviceId
                })
            })
            .then(response => {
                if (response.ok) {
                    alert("Device berhasil dipilih!");
                    window.location.href = "{{ route('register.index') }}"; // ✅ Redirect ke halaman register
                } else {
                    return response.json().then(data => {
                        alert(`Gagal memilih device: ${data.error || 'Terjadi kesalahan.'}`);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Terjadi kesalahan saat memilih device.");
            });
        }
    }
</script>
>>>>>>> ca26df1 (regis sudah bisa konek ke device)
=======
>>>>>>> a916abf (show data di list device  yang diinput di regis)
