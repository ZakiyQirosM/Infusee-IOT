@extends('layouts.main')

@section('title', 'Pilih Device')

@section('content')
<div class="container">
    <div class="row">
    {{-- ✅ Kolom kiri untuk data pasien --}}
        <div class="col-md-5">
            <div class="patient-info">
                <h2>Data Pasien</h2>
                @if ($patientData)
                <div class="form-group device">
                    <label class="register-label">No. Registrasi</label>
                    <div class="register-input-container device">
                        <span>:</span>
                        <span class="register-text device">{{ $patientData['no_reg_pasien'] }}</span>
                    </div>
                </div>

                <div class="form-group device">
                    <label class="register-label">Nama</label>
                    <div class="register-input-container device">
                        <span>:</span>
                        <span class="register-text device">{{ $patientData['nama_pasien'] }}</span>
                    </div>
                </div>

                <div class="form-group device">
                    <label class="register-label">Umur</label>
                    <div class="register-input-container device">
                        <span>:</span>
                        <span class="register-text device">{{ $patientData['umur'] }} Tahun</span>
                    </div>
                </div>

                <div class="form-group device">
                    <label class="register-label">No. Ruangan</label>
                    <div class="register-input-container device">
                        <span>:</span>
                        <span class="register-text device">{{ $patientData['no_ruangan'] }}</span>
                    </div>
                </div>

                <div class="form-group device">
                    <label class="register-label">Durasi Infus</label>
                    <div class="register-input-container device">
                        <span>:</span>
                        <span class="register-text device">{{ $patientData['durasi_infus_menit'] }} menit</span>
                    </div>
                </div>

                @else
                    <p class="alert alert-warning no-data">Tidak ada data pasien yang aktif.</p>
                @endif
            </div>
        </div>

        {{-- ✅ Kolom kanan untuk list device --}}
        <div class="col-md-7">
            <h2>Pilih Device untuk Pasien</h2>
            @if ($devices->isEmpty())
                <p class="alert alert-warning no-data">Tidak ada device aktif.</p>
            @else
                <div class="device-list">
                @foreach ($devices as $device)
                    <div class="device-card" 
                        data-id="{{ $device->id_perangkat_infusee }}" 
                        onclick="selectDevice('{{ $device->id_perangkat_infusee }}')">
                        <div class="device-info">
                            <h3>DIF: {{ $device->id_perangkat_infusee }}</h3>
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

