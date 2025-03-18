@extends('layouts.main')

@section('title', 'Device List')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Pilih Perangkat Aktif</h1>

    @if (count($activeDevices) > 0)
        <div class="device-list">
            @foreach ($activeDevices as $device)
                <button class="device-btn" onclick="selectDevice('{{ $device['id'] }}', '{{ $device['ip'] }}')">
                    {{ $device['id'] }} - {{ $device['ip'] }}
                </button>
            @endforeach
        </div>
    @else
        <p class="text-center">Tidak ada perangkat aktif yang tersedia.</p>
    @endif
</div>

<script>
    function selectDevice(id, ip) {
        alert(`Perangkat ${id} dengan IP ${ip} dipilih`);
        // Redirect ke halaman infusee berdasarkan ID perangkat
        window.location.href = @json(route('infusee.index'));
    }
</script>


{{-- Styling --}}
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
@endsection
