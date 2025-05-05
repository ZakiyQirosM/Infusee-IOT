@extends('layouts.main')

@section('title', 'Log Activity')

@section('content')

<div class="activity-container mt-4 px-5">
    <!-- Header: Title + Search -->
    <div class="activity-header d-flex justify-content-between align-items-center mb-3">
        <h4 class="activity-title">Log Aktivitas Infus</h4>

        <!-- Search Form -->
        <div class="search-container">
            <form action="{{ route('activity.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai atau pasien...">
                <button type="submit">Cari</button>
            </form>
        </div>
    </div>

    <!-- Table -->
    <table class="activity-table table table-bordered">
        <thead>
            <tr class="activity-header-row">
                <th class="activity-th">
                    <a href="{{ route('activity.index', ['sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}"
                       class="sort-button">
                        Waktu {{ request('sort') == 'asc' ? '⬇' : '⬆' }}
                    </a>
                </th>
                <th class="activity-th">Nama Pegawai</th>
                <th class="activity-th">Nama Pasien</th>
                <th class="activity-th">ID Perangkat</th>
                <th class="activity-th">Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr class="activity-data-row">
                    <td class="activity-td">{{ $log->created_at }}</td>
                    <td class="activity-td">{{ $log->pegawai->nama_peg ?? '-' }}</td>
                    <td class="activity-td">{{ $log->session->patient->nama_pasien ?? '-' }}</td>
                    <td class="activity-td">{{ $log->session->id_perangkat_infusee ?? '-' }}</td>
                    <td class="activity-td 
                        @if($log->aktivitas == 'Memulai sesi infus') start-activity 
                        @elseif($log->aktivitas == 'Mengakhiri sesi infus') end-activity
                        @endif">
                        {{ $log->aktivitas ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination d-flex justify-content-center">
        {{ $logs->appends(request()->query())->links('vendor.pagination.custom') }}
    </div>

</div>
@endsection
