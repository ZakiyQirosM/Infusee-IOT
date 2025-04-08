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

                <div class="clear-patient-wrapper-left">
                    <form action="{{ route('infusion.clear', $infusionSession->id_session) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pasien ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#dc3545">
                                <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360Z"/>
                            </svg>
                        </button>
                    </form>
                </div>


                @else
                    <p class="alert alert-warning no-data">Tidak ada data pasien yang aktif.</p>
                @endif
            </div>
        </div>

        {{-- ✅ Kolom kanan untuk list device --}}
        <div class="col-md-7">
            <h2>Device Aktif</h2>
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

