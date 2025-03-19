@extends('layouts.main')

@section('title', 'Monitoring Infus Pasien')

@section('content')
<div class="container">
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
                    <p>No Kamar: {{$infusee ['no_ruangan']}}</p>
                </div>
                <div class="right">
                    <p>TPM: {{ $infusee ['laju_tetes_tpm']}}</p>
                </div>
            </div>

            {{-- Chart di tengah --}}
            <div class="chart-container">
                <canvas id="chart-{{ $index }}" width="300" height="300"></canvas>
            </div>

            {{-- Timer --}}
            <div class="Timer">
                <P class="labtime">Waktu Infus:</p>
                <p class="timer">{{ $infusee['timestamp_infus'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Script Chart.js --}}
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
                        tooltip: {
                            enabled: true
                        },
                        legend: {
                            display: false
                        },
                        centerText: true
                    }
                }
            });
        @endforeach
    });
</script>
@endsection
