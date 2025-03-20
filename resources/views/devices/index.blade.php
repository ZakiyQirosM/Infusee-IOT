@extends('layouts.main')

@section('title', 'Pilih Device')

@section('content')
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
</div>

{{-- ✅ CSRF Token untuk keperluan POST request --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection
