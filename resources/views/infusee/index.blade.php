@extends($layout)

@section('title', 'Monitoring Infus Pasien')

@section('content')

<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="heading">Monitoring Infus Pasien</h1>
    {{-- Overlay Konfirmasi (Pindah ke luar card) --}}
    <div id="confirm-overlay" class="confirm-overlay">
        <div class="confirm-popup">
            <h3>Yakin ingin mengakhiri sesi ini?</h3>
            <button type="button" class="popup-btn confirm-btn" id="confirmYes">Ya, Akhiri</button>
            <button type="button" class="popup-btn cancel-btn" id="confirmNo">Batal</button>
        </div>
    </div>
    <div class="grid">
    @foreach($infusees as $index => $infusee)
        <div class="card" id="card-{{ $index }}">
            <div class="card-header">
                <div class="left">
                    <h4>NoK: {{ $infusee['no_ruangan'] }}</h4>
                </div>
                <div class="right">
                    <span>
                        {{ str_repeat('*', strlen($infusee['no_reg_pasien']) - 3) . substr($infusee['no_reg_pasien'], -3) }}
                    </span>
                </div>
            </div>

            {{-- Divider --}}
            <div class="divider"></div>

            {{-- Footer --}}
            <div class="card-footer">
                <div class="left">
                    <p>{{ maskNama($infusee['nama_pasien']) }}</p>
                </div>
                <div style="
                    display: flex; 
                    align-items: center; 
                    gap: 4px; 
                    background-color: {{ $infusee['bgColor'] }}; 
                    padding: 6px; 
                    border-radius: 6px; 
                    color: #ffffff;">
                    <i class="{{ $infusee['icon'] }}" style="font-size: 10px;"></i>
                    <p style="margin: 0; font-size: 11px; font-weight:bold">
                        TPM: <span id="tpm-sensor-{{ $index }}">{{ $infusee['tpm_sensor'] }}</span> / 
                        <span id="tpm-prediksi-{{ $index }}">{{ $infusee['tpm_prediksi'] }}</span>
                    </p>
                </div>
            </div>

            {{-- Chart --}}
            <div class="chart-container">
                <canvas id="chart-{{ $index }}" width="300" height="300"></canvas>
            </div>


            {{-- Timer --}}
            <div class="Timer">
                <p class="labtime">Waktu Infus Berjalan:</p>
                <p id="timer-{{ $index }}" class="timer" 
                   data-start-time="{{ \Carbon\Carbon::parse($infusee['timestamp_infus'])->format('Y-m-d\TH:i:sP') }}">
                   <svg xmlns="http://www.w3.org/2000/svg" height="36px" viewBox="0 -960 960 960" width="36px" fill="#CCCCCC"><path d="M480-120q-75 0-140.5-28.5t-114-77q-48.5-48.5-77-114T120-480q0-75 28.5-140.5t77-114q48.5-48.5 114-77T480-840q82 0 155.5 35T760-706v-94h80v240H600v-80h110q-41-56-101-88t-129-32q-117 0-198.5 81.5T200-480q0 117 81.5 198.5T480-200q105 0 183.5-68T756-440h82q-15 137-117.5 228.5T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/></svg>
                </p>
            </div>

            {{-- Alert (Hidden by default) --}}
            <div id="alert-{{ $index }}" class="alert alert-persen" style="display: none;">
                ⚠️ Infus hampir habis! Segera periksa!
            </div>

            {{-- Tombol End Sesi --}}
                <form id="end-session-{{ $index }}" action="{{ route('infusee.endSession', $infusee['id_session']) }}" method="POST" style="display: none; margin-top: 10px;">
                    @csrf
                    <button type="button" class="end-session-btn" onclick="openConfirmPopup('end-session-{{ $index }}')">
                        Akhiri Sesi
                    </button>
                </form>
        </div>
        @endforeach
    </div>
</div>

{{-- Script Chart.js dan Timer --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.register({
        id: 'centerText',
        beforeDraw(chart) {
            const { width, height } = chart;
            const ctx = chart.ctx;
            
            ctx.restore();
            const fontSize = (height / 100).toFixed(2);
            ctx.font = `bold ${fontSize}em sans-serif`;
            ctx.textBaseline = 'middle';

            const value = chart.data.datasets[0].data[0];
            const text = `${value.toFixed(0)}%`;
            const textX = Math.round((width - ctx.measureText(text).width) / 2);
            const textY = height / 2;

            ctx.fillStyle = getColorBasedOnPercentage(value);
            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    });

    function getColorBasedOnPercentage(value) {
        if (value >= 80) return '#00cc44'; // Hijau
        if (value >= 60) return '#ffcc00'; // Kuning
        if (value >= 40) return '#ff9900'; // Oranye
        if (value >= 11) return '#ff3333'; // Merah
        return '#000000'; // Hitam
    }

    let chartInstances = {};
    let timerInstances = {};

    function createChart(index, value, color) {
        const ctx = document.getElementById(`chart-${index}`).getContext('2d');
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
                    legend: { display: false },
                    centerText: true
                }
            }
        });
    }

    // Menghitung waktu berjalan
    function calculateElapsedTime(startTimestamp, durationSeconds) {
        const currentTime = new Date().getTime();
        const elapsedTime = Math.floor((currentTime - startTimestamp) / 1000); 

        if (elapsedTime >= durationSeconds) {
            return null;
        }

        const hours = Math.floor(elapsedTime / 3600);
        const minutes = Math.floor((elapsedTime % 3600) / 60);
        const seconds = elapsedTime % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    // update chart
    function updateChart(index, value, sessionEnded = false) {
        if (chartInstances[index] && value !== undefined && value !== null) {
            chartInstances[index].data.datasets[0].data = [value, 100 - value];
            chartInstances[index].update();

            const alertElement = document.getElementById(`alert-${index}`);
            if (!sessionEnded && value <= 10) {
                alertElement.style.display = 'block';
            } else {
                alertElement.style.display = 'none';
            }
        }
    }

    function startTimer(index, startTimestamp, durationMinutes) {
        const durationSeconds = durationMinutes * 3600;
        
        if (!timerInstances[index]) {
            const timerEl = document.getElementById(`timer-${index}`);
            const endSessionForm = document.getElementById(`end-session-${index}`);
            const card = document.getElementById(`card-${index}`);

            card.addEventListener('click', (e) => {
                if (timerEl.innerText === 'Sesi Infus Selesai') {
                    endSessionForm.style.display = 'block';
                    e.stopPropagation();
                }
            });

            document.addEventListener('click', function (e) {
                if (!card.contains(e.target) && !endSessionForm.contains(e.target)) {
                    endSessionForm.style.display = 'none';
                }
            });

            timerInstances[index] = setInterval(() => {
                let timerText = calculateElapsedTime(startTimestamp, durationSeconds);
                if (timerText === null) {
                    timerEl.innerText = 'Sesi Infus Selesai';
                    clearInterval(timerInstances[index]);
                    timerInstances[index] = null;
                } else {
                    timerEl.innerText = timerText;
                }
            }, 1000);
        }
    }

    document.getElementById('confirmYes').addEventListener('click', () => {
        if (currentFormId) {
            console.log(`Submitting form: ${currentFormId}`);
            document.getElementById(currentFormId).submit();
        }
        closeConfirmPopup();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const confirmYes = document.getElementById('confirmYes');
        const confirmNo = document.getElementById('confirmNo');
        
        if (confirmYes && confirmNo) {
            confirmYes.addEventListener('click', () => {
                if (currentFormId) {
                    console.log(`Submitting form: ${currentFormId}`);
                    document.getElementById(currentFormId).submit();
                }
                closeConfirmPopup();
            });

            confirmNo.addEventListener('click', () => {
                closeConfirmPopup();
            });
        } else {
            console.error('Element confirmYes atau confirmNo tidak ditemukan!');
        }

        @foreach ($infusees as $index => $infusee)
            (() => {
                const index = {{ $index }};
                const startTimestamp = new Date('{{ $infusee['timestamp_infus'] }}').getTime();
                const initialValue = {{ $infusee['persentase_infus'] }};
                const color = '{{ $infusee['color'] }}';
                const durationMinutes = {{ $infusee['durasi_infus_jam'] }};
                createChart(index, initialValue, color);
                startTimer(index, startTimestamp, durationMinutes); 
            })();
        @endforeach
    });

    setInterval(() => {
        fetch('/get-latest-infus')
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                console.log('Data received:', data);
                data.forEach((infus) => {
                    const cardElement = document.querySelector(`form[id^="end-session-"][action$="${infus.id_session}"]`);
                    
                    if (cardElement) {
                        const index = cardElement.id.split('-')[2];
                        const value = infus.persentase_infus;
                        const color = getColorBasedOnPercentage(value);

                        console.log(`Updating card ${index} with value ${value}`);
                        
                        // Update chart
                        if (chartInstances[index]) {
                            chartInstances[index].data.datasets[0].data = [value, 100 - value];
                            chartInstances[index].data.datasets[0].backgroundColor[0] = color;
                            chartInstances[index].update();
                        }

                        // Update TPM values dan style
                        const tpmContainer = document.querySelector(`#card-${index} .card-footer > div[style*="background-color"]`);
                        if (tpmContainer) {
                            tpmContainer.style.backgroundColor = infus.bgColor;
                            
                            const iconElement = tpmContainer.querySelector('i');
                            if (iconElement) {
                                iconElement.className = infus.icon;
                                iconElement.style.fontSize = '10px';
                            }
                        }

                        document.getElementById(`tpm-sensor-${index}`).innerText = infus.tpm_sensor;
                        document.getElementById(`tpm-prediksi-${index}`).innerText = infus.tpm_prediksi;

                        const alertElement = document.getElementById(`alert-${index}`);
                        if (alertElement) {
                            alertElement.style.display = value <= 10 ? 'block' : 'none';
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching or processing data:', error);
            });
    }, 60000);
</script>
@endsection


