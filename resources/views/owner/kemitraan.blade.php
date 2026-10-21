@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
    <div class="page-header"><h2><i class="ph-fill ph-handshake" style="color:#ff0000;"></i> Pengajuan Mitra Baru</h2></div>
    <div class="card">
        <table class="native-table" style="min-width:850px;"><thead><tr><th>No</th><th>Tanggal Masuk</th><th>Nama Lengkap</th><th>Lokasi Usaha</th><th>Pesan</th><th>Aksi</th></tr></thead><tbody>
            @php $no = 1; @endphp
            @foreach ($pengajuan as $p)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $p->tanggal ? \Illuminate\Support\Carbon::parse($p->tanggal)->translatedFormat('d M Y') : '-' }}</td>
                    <td><b>{{ $p->nama }}</b></td>
                    <td>{{ $p->lokasi_usaha }}</td>
                    <td>{{ strlen($p->pesan) > 55 ? substr($p->pesan, 0, 55) . '...' : $p->pesan }}</td>
                    <td style="display:flex; gap:8px;">
                        {{-- Tombol hubungi via WhatsApp --}}
                        <a class="btn btn-wa" target="_blank" href="https://wa.me/{{ $p->whatsapp }}"><i class="ph-fill ph-whatsapp-logo"></i> Hubungi</a>
                        <form method="POST" action="{{ route('owner.kemitraan.hapus', ['id' => $p->id]) }}" class="form-confirm">
                            @csrf
                            <button class="btn btn-del"><i class="ph-bold ph-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection
