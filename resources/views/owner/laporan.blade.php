@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-money" style="color:#ff0000;"></i> Laporan Keuangan</h2></div>

    {{-- Filter periode laporan berdasarkan bulan dan tahun --}}
    <div class="native-filter">
        <form method="GET" action="{{ route('owner.laporan_full') }}">
            <div>
                <select name="bulan">
                    @foreach ($listBulan as $k => $v)
                        <option value="{{ $k }}" {{ $bulan == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="number" name="tahun" value="{{ $tahun }}">
            </div>
            <button class="btn btn-add" type="submit"><i class="ph-bold ph-funnel"></i> Filter Data</button>
        </form>
    </div>

    @php $totalBersih = 0; @endphp
    <div class="card">
        <table class="native-table">
            <thead><tr><th>Tanggal</th><th>Outlet</th><th>Omzet Kotor</th><th>Pengeluaran</th><th>Bersih</th><th>Catatan</th></tr></thead>
            <tbody>
                @if ($laporan->isEmpty())
                    <tr><td colspan="6" class="native-empty">Tidak ada data laporan di periode ini.</td></tr>
                @endif
                @foreach ($laporan as $l)
                    @php $totalBersih += (int) $l->omzet_bersih; @endphp
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($l->tanggal)->translatedFormat('d M Y') }}</td>
                        <td><b>{{ $l->kode_outlet }}</b></td>
                        <td>Rp {{ number_format($l->omzet_kotor, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($l->pengeluaran, 0, ',', '.') }}</td>
                        <td><b>Rp {{ number_format($l->omzet_bersih, 0, ',', '.') }}</b></td>
                        <td>{{ $l->catatan }}</td>
                    </tr>
                @endforeach
                <tr class="native-total-row"><td colspan="4" style="text-align:right;">TOTAL BERSIH:</td><td colspan="2" style="color:#1b7f2a;">Rp {{ number_format($totalBersih, 0, ',', '.') }}</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
