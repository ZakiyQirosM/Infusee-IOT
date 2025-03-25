@extends('layouts.main')

@section('title', 'Monitoring Infus Pasien')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="heading">Monitoring Infus Pasien</h1>
    
    <div class="grid">
        @foreach($infusees as $index => $infusee)
        <div class="card">
            {{-- Header --}}
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

            {{-- Line hijau tipis --}}
            <div class="divider"></div>

            {{-- Footer --}}
            <div class="card-footer">
                <div class="left">
                    <p>No Kamar: {{ $infusee['no_ruangan'] }}</p>
                </div>
                <div class="right">
                    <p>TPM: {{ $infusee['laju_tetes_tpm'] }}</p>
                </div>
            </div>

            {{-- Chart di tengah --}}
            <div class="chart-container">
                <canvas id="chart-{{ $index }}" width="300" height="300"></canvas>
            </div>

            {{-- Timer --}}
            <div class="Timer">
                <p class="labtime">Waktu Infus:</p>
                <p id="timer-{{ $index }}" class="timer" 
                data-start-time="{{ \Carbon\Carbon::parse($infusee['timestamp_infus'])->format('Y-m-d\TH:i:sP') }}">
                    {{ \Carbon\Carbon::parse($infusee['timestamp_infus'])->setTimezone('Asia/Jakarta')->format('H:i:s') }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ✅ Plugin untuk menampilkan teks di tengah chart
    Chart.register({
        id: 'centerText',
        beforeDraw(chart) {
            const { width } = chart;
            const { height } = chart;
            const ctx = chart.ctx;

            ctx.restore();
            const fontSize = (height / 100).toFixed(2);
            ctx.font = `${fontSize}em sans-serif`;
            ctx.textBaseline = 'middle';

            const text = `${chart.data.datasets[0].data[0].toFixed(1)}%`;
            const textX = Math.round((width - ctx.measureText(text).width) / 2);
            const textY = height / 2;

            ctx.fillStyle = '#333';
            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    });

    let chartInstances = {}; // ✅ Simpan instance chart untuk tiap input
    let timerInstances = {}; // ✅ Simpan instance timer untuk tiap input

    // ✅ Buat chart dengan nilai awal
    function createChart(index, value, color) {
        const ctx = document.getElementById(`chart-${index}`).getContext('2d');

        // 🛑 Hapus chart lama jika sudah ada
        if (chartInstances[index]) {
            chartInstances[index].destroy();
        }

        chartInstances[index] = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Sisa', 'Terpakai'],
                datasets: [{
                    data: [value, 100 - value],
                    backgroundColor: [color, '#e0e0e0'],
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
    }

    // ✅ Fungsi untuk update nilai chart tanpa API
    function updateChart(index, value) {
        if (chartInstances[index] && value !== undefined && value !== null) {
            chartInstances[index].data.datasets[0].data = [value, 100 - value];
            chartInstances[index].update();
        }
    }

    // ✅ Fungsi untuk menghitung waktu berjalan
function calculateElapsedTime(startTimestamp) {
    const currentTime = new Date().getTime();
    const elapsedTime = Math.floor((currentTime - startTimestamp) / 1000); // dalam detik
    
    const hours = Math.floor(elapsedTime / 3600);
    const minutes = Math.floor((elapsedTime % 3600) / 60);
    const seconds = elapsedTime % 60;

    // ✅ Format waktu dengan leading zero
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
}

// ✅ Fungsi untuk memulai timer dan update chart
function startTimer(index, startTimestamp, initialValue) {
    // 🛑 Hentikan timer lama jika sudah ada
    if (timerInstances[index]) {
        clearInterval(timerInstances[index]);
    }

    // ✅ Tampilkan nilai awal langsung (tanpa menunggu interval pertama)
    document.getElementById(`timer-${index}`).innerText = calculateElapsedTime(startTimestamp);

    // ✅ Jalankan `setInterval()` untuk update tiap 1 detik
    timerInstances[index] = setInterval(() => {
        // ✅ Update nilai timer tiap 1 detik
        document.getElementById(`timer-${index}`).innerText = calculateElapsedTime(startTimestamp);

        // ✅ Update chart langsung dari nilai awal
        updateChart(index, initialValue);
    }, 1000);
}

// ✅ Jalankan setelah DOM siap
document.addEventListener('DOMContentLoaded', () => {
    @foreach ($infusees as $index => $infusee)
        (() => {
            const index = {{ $index }};
            const startTimestamp = new Date('{{ $infusee['timestamp_infus'] }}').getTime();
            const initialValue = {{ $infusee['persentase_infus_menit'] }};
            const color = '{{ $infusee['color'] }}';

            // ✅ Buat chart dengan nilai awal
            createChart(index, initialValue, color);

            // ✅ Timer mulai dari timestamp awal
            startTimer(index, startTimestamp, initialValue);
        })();
    @endforeach
});

</script>
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> d7510f2 (add file migration, model, dan controler, serta cari data pasien bisa)
