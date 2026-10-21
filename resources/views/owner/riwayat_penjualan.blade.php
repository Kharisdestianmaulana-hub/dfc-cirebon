@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-receipt" style="color:#ff0000;"></i> Riwayat Penjualan</h2></div>

    {{-- Filter rentang tanggal dan outlet --}}
    <div class="native-filter">
        <form method="GET" action="{{ route('owner.riwayat_penjualan') }}" style="grid-template-columns: repeat(4, minmax(0,1fr));">
            <div><label>Dari Tanggal</label><input type="date" name="tgl_mulai" value="{{ $tglMulai }}"></div>
            <div><label>Sampai Tanggal</label><input type="date" name="tgl_akhir" value="{{ $tglAkhir }}"></div>
            <div>
                <label>Pilih Outlet</label>
                <select name="outlet">
                    <option value="semua">-- Semua Outlet --</option>
                    @foreach ($outlets as $o)
                        <option value="{{ $o->username_admin }}" {{ $filterOutlet === $o->username_admin ? 'selected' : '' }}>{{ $o->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-add" type="submit"><i class="ph-bold ph-funnel"></i> Tampilkan</button>
        </form>
    </div>

    <div class="card"><table class="native-table" style="min-width:1000px;"><thead><tr><th>Waktu Pesan</th><th>Order ID & Pemesan</th><th>Outlet</th><th>Detail Rincian (JSON)</th><th>Status</th><th>Total Bayar</th></tr></thead><tbody>
        @php $total = 0; $count = 0; @endphp
        @foreach ($orders as $o)
            @php $total += (int) $o->total_bayar; $count++; @endphp
            <tr>
                <td>{{ \Illuminate\Support\Carbon::parse($o->waktu_pesan)->translatedFormat('d M Y, H:i') }}</td>
                <td><b>{{ $o->order_id }}</b><br>{{ $o->nama_pemesan }}<br><small>{{ $o->no_hp }}</small></td>
                <td>{{ $o->kode_outlet }}</td>
                <td><code style="font-size:11px; white-space:normal;">{{ substr($o->detail_pesanan ?? '-', 0, 120) }}</code></td>
                <td>{{ $o->status_pesanan }}</td>
                <td><b>Rp {{ number_format($o->total_bayar, 0, ',', '.') }}</b></td>
            </tr>
        @endforeach
        @if ($count === 0)
            <tr><td colspan="6" class="native-empty">Tidak ada transaksi pesanan web di periode ini.</td></tr>
        @endif
        <tr class="native-total-row"><td colspan="4" style="text-align:right;">TOTAL TRANSAKSI: &nbsp; {{ $count }} Pesanan</td><td colspan="2" style="color:#ff0000;">Rp {{ number_format($total, 0, ',', '.') }}<br><small style="color:#777; font-size:11px;">*Hanya dari pesanan web</small></td></tr>
    </tbody></table></div>
</div>
@endsection
