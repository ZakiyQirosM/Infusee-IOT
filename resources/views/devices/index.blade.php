@extends('layouts.main')

@section('title', 'Device List')

@section('content')
{{-- Jika tidak ada device --}}
@if ($activeDevices && $activeDevices->isNotEmpty())
    <div class="device-list">
        @foreach ($activeDevices as $device)
            <div class="device-card" data-device-id="{{ $device->id_perangkat_infusee }}">
                <p>{{ $device->alamat_ip_infusee }}</p>
            </div>
        @endforeach
    </div>
@else
    <p class="no-device">Tidak ada device aktif.</p>
@endif
@endsection

{{-- JavaScript dipindah ke push scripts agar lebih rapi --}}
@push('scripts')
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
@endpush

{{-- Styling --}}
@push('styles')
<style>
    .container {
        max-width: 800px;
        margin: 20px auto;
    }

    h1 {
        color: #333;
    }

    .device-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .device-card {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        background: #f9f9f9;
    }

    .device-btn {
        background-color: #00C7B4;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .device-btn:hover {
        background-color: #14967F;
    }

    .text-center {
        text-align: center;
        color: #777;
        margin-top: 20px;
    }
</style>
@endpush
