@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-eye" style="color:#ff0000;"></i> Log Aktivitas Karyawan</h2></div>
    <div class="card"><table class="native-table" style="min-width:850px;"><thead><tr><th>Waktu</th><th>Pelaku</th><th>Aksi</th><th>Detail Aktivitas</th></tr></thead><tbody>
        @foreach ($logs as $log)
            @php
                // Tentukan warna lencana berdasarkan jenis aksi.
                $aksiLower = strtolower($log->aksi);
                $class = 'log-login';
                $dot = '';
                if (strpos($aksiLower, 'stok') !== false || strpos($aksiLower, 'penjualan') !== false) { $class = 'log-stok'; $dot = 'orange'; }
                if (strpos($aksiLower, 'status') !== false) { $class = 'log-status'; $dot = 'orange'; }
            @endphp
            <tr>
                <td><span class="log-time">{{ \Illuminate\Support\Carbon::parse($log->waktu)->format('H:i:s') }}</span><span class="log-date">{{ \Illuminate\Support\Carbon::parse($log->waktu)->translatedFormat('d M Y') }}</span></td>
                <td><span class="log-dot {{ $dot }}"></span><b>{{ ucfirst($log->pelaku) }}</b></td>
                <td><span class="log-action {{ $class }}">{{ $log->aksi }}</span></td>
                <td>{{ $log->detail }}</td>
            </tr>
        @endforeach
    </tbody></table></div>
</div>
@endsection
