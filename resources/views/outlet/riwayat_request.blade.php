{{-- Potongan tampilan riwayat request stok, dimuat berkala lewat AJAX --}}
@if ($requestHistory->isEmpty())
    <p style="color:#999; font-size:12px; text-align:center;">Belum pernah request.</p>
@else
    @foreach ($requestHistory as $hr)
        @php
            // Tentukan warna lencana sesuai status permintaan.
            $stat = $hr->status;
            if ($stat == 'Pending') {
                $cStat = 'background:#fff3e0; color:#e65100;';
            } elseif ($stat == 'Disetujui') {
                $cStat = 'background:#e8f5e9; color:#2e7d32;';
            } else {
                $cStat = 'background:#ffebee; color:#c62828;';
            }
        @endphp
        <div class="history-item">
            <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                <strong style="color:#1565c0;">{{ $hr->jumlah . ' ' . $hr->satuan . ' ' . $hr->nama_barang }}</strong>
                <span class="req-status-badge" style="{{ $cStat }}">{{ $stat }}</span>
            </div>
            <div class="history-time">{{ $hr->waktu_request->translatedFormat('d M Y, H:i') }}</div>
            @if ($stat == 'Ditolak')
                <div style="font-size:11px; color:#c62828; margin-top:3px; font-style:italic;">Alasan: {{ $hr->alasan_tolak }}</div>
            @endif
        </div>
    @endforeach
@endif
