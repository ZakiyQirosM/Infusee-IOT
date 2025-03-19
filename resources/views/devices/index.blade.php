@extends('layouts.main')

@section('title', 'Pilih Device')

@section('content')
<div class="device-container">
    <h2>Pilih Device untuk Pasien</h2>

<<<<<<< HEAD
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
            </div>
        @endforeach
    </div>
    @endif
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
