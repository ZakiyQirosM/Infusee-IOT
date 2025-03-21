@extends('layouts.main')

@section('title', 'Pilih Device')

@section('content')
<div class="container">
    <div class="row">
        {{-- ✅ Kolom kiri untuk data pasien --}}
        <div class="col-md-5">
            <div class="patient-info">
                <h2>Data Pasien</h2>
                @if (session('infusion_session') && $patient)
                    <p><strong>No. Registrasi:</strong> {{ $infusionSession->no_reg_pasien }}</p>
                    <p><strong>Nama:</strong> {{ $patient->nama_pasien }}</p>
                    <p><strong>Umur:</strong> {{ $patient->umur }}</p>
                    <p><strong>No. Ruangan:</strong> {{ $patient->no_ruangan }}</p>
                    <p><strong>Durasi Infus:</strong> {{ $patient->durasi_infus_menit }} menit</p>
                @else
                    <p class="alert alert-warning no-device">Tidak ada data pasien aktif.</p>
                @endif
            </div>
        </div>

        {{-- ✅ Kolom kanan untuk list device --}}
        <div class="col-md-7">
            <h2>Pilih Device untuk Pasien</h2>
            @if ($devices->isEmpty())
                <p class="alert alert-warning no-device">Tidak ada device aktif.</p>
            @else
                <div class="device-list">
                @foreach ($devices as $device)
                    <div class="device-card" 
                        data-id="{{ $device->id_perangkat_infusee }}" 
                        onclick="selectDevice('{{ $device->id_perangkat_infusee }}')">
                        <div class="device-info">
                            <h3>ID: {{ $device->id_perangkat_infusee }}</h3>
                            <p>IP: {{ $device->alamat_ip_infusee }}</p>
                        </div>
                    </div>
                @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ✅ CSRF Token untuk keperluan POST request --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
