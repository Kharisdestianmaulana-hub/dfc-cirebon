@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-bell-ringing" style="color:#ff0000;"></i> Permintaan Stok Outlet</h2></div>
    <div class="card">
        <table class="native-table" style="min-width:1000px;"><thead><tr><th>Waktu Request</th><th>Nama Outlet</th><th>Barang Diminta</th><th>Status</th><th>Catatan / Alasan</th><th>Aksi Owner</th></tr></thead><tbody>
            @foreach ($requests as $r)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($r->waktu_request ?? 'now')->translatedFormat('d M Y, H:i') }}</td>
                    <td><b>{{ $r->nama_outlet }}</b></td>
                    <td style="color:#1565c0; font-weight:800;">{{ number_format($r->jumlah) }} {{ $r->satuan }} {{ $r->nama_barang }}</td>
                    <td><span class="native-status {{ $r->status === 'Disetujui' ? 'status-disetujui' : ($r->status === 'Ditolak' ? 'status-ditolak' : 'status-pending') }}">{{ $r->status }}</span></td>
                    <td>{{ $r->alasan_tolak ?? '-' }}</td>
                    <td>
                        @if ($r->status === 'Pending')
                            {{-- Tombol setujui dan tolak permintaan --}}
                            <form method="POST" action="{{ route('owner.request_stok.approve') }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="id_request" value="{{ $r->id }}">
                                <button class="btn btn-edit">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('owner.request_stok.tolak') }}" style="display:inline-flex; gap:6px;">
                                @csrf
                                <input type="hidden" name="id_request" value="{{ $r->id }}">
                                <input type="text" name="alasan" placeholder="Alasan" style="width:120px; padding:8px; border:1px solid #ddd; border-radius:6px;">
                                <button class="btn btn-del">Tolak</button>
                            </form>
                        @else
                            <span style="color:#999;"><i class="ph-bold ph-check-circle"></i> Selesai diproses</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection
