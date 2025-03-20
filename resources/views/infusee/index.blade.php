@extends('layouts.main')

@section('title', 'Monitoring Infus Pasien')

@section('content')
<div class="container">
    <h1 class="heading">Monitoring Infus Pasien</h1>
    
    <div class="grid">
        @foreach($infusees as $index => $infusee)
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <div class="left">
                    <h4>{{ $infusee['nama_pasien'] }}</h4>
                </div>
                <div class="right">
                    <span>
                        <i class="fa-solid fa-circle-check status-icon"></i>
                        {{ $infusee['id_perangkat_infusee'] }}
                    </span>
                </div>
            </div>

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Footer -->
            <div class="card-footer">
                <div class="left">
                    <p>No Kamar: {{ $infusee['no_ruangan'] }}</p>
                </div>
                <div class="right">
                    <p>TPM: {{ $infusee['laju_tetes_tpm'] }}</p>
                </div>
            </div>

            <!-- Chart -->
            <div class="chart-container">
                <canvas id="chart-{{ $index }}" width="300" height="300"></canvas>
            </div>

            <!-- Timer -->
            <div class="Timer">
                <p class="labtime">Waktu Infus:</p>
                <p class="timer">{{ $infusee['timestamp_infus'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Device Selection -->
<div class="device-container">
    <h2>Pilih Device untuk Pasien</h2>
    @if ($devices->isEmpty())
        <p class="no-device">Tidak ada device aktif.</p>
    @else
        <div class="device-list">
            @foreach ($devices as $device)
                <div class="device-card" onclick="selectDevice('{{ $device->id_perangkat_infusee }}')">
                    <div class="device-info">
                        <h3>ID: {{ $device->id_perangkat_infusee }}</h3>
                        <p>IP: {{ $device->alamat_ip_infusee }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Script Chart.js -->
<script>
    Chart.register({
        id: 'centerText',
        beforeDraw: (chart) => {
            const { width, height } = chart;
            const ctx = chart.ctx;
            const text = chart.config.data.datasets[0].data[0] + '%';

            ctx.restore();
            ctx.font = 'bold 22px Arial';
            ctx.fillStyle = '#333';
            ctx.textBaseline = 'middle';

            const textX = Math.round((width - ctx.measureText(text).width) / 2);
            const textY = height / 2;

            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        @foreach ($infusees as $index => $infusee)
            const ctx{{ $index }} = document.getElementById('chart-{{ $index }}').getContext('2d');
            new Chart(ctx{{ $index }}, {
                type: 'doughnut',
                data: {
                    labels: ['Sisa', 'Terpakai'],
                    datasets: [{
                        data: [{{ $infusee['persentase_infus_menit'] }}, {{ 100 - $infusee['persentase_infus_menit'] }}],
                        backgroundColor: ['{{ $infusee['color'] }}', '#e0e0e0'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        tooltip: { enabled: true },
                        legend: { display: false },
                        centerText: true
                    }
                }
            });
        @endforeach
    });

    function selectDevice(deviceId) {
        if (confirm(`Pilih device dengan ID: ${deviceId}?`)) {
            fetch("{{ route('devices.assign') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ device_id: deviceId })
            })
            .then(response => response.ok ? alert("Device berhasil dipilih!") : response.json().then(data => alert(`Gagal memilih device: ${data.error || 'Terjadi kesalahan.'}`)))
            .catch(error => alert("Terjadi kesalahan saat memilih device."));
        }
    }
</script>

<!-- Styling -->
<style>
    .container {
        max-width: 1200px;
        margin: 50px auto;
    }
    .heading {
        color: #777;
        text-align: center;
    }
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }
    .card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 20px;
        transition: transform 0.3s ease;
    }
    .card:hover { transform: translateY(-5px); }
    .card-header, .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        color: #555;
    }
    .divider {
        height: 2px;
        background-color: #00C7B4;
        border-radius: 1px;
        margin-top: 8px;
    }
    .status-icon { color: #00cc44; font-size: 16px; margin-left: 5px; }
    .labtime, .timer { text-align: center; font-weight: bold; }
    .timer { font-size: 32px; color: #00C7B4; animation: pulse 1.5s infinite; }
    .chart-container { display: flex; justify-content: center; margin-top: 5px; }
    .device-card { cursor: pointer; padding: 15px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 10px; transition: 0.3s; }
    .device-card:hover { background-color: #f5f5f5; }
</style>
@endsection
