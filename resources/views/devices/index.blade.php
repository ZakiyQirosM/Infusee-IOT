@extends('layouts.main')

@section('title', 'Pilih Device')

@section('content')
<div class="device-container">
    <h2>Pilih Device untuk Pasien</h2>

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
