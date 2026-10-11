@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-map-pin" style="color:#ff0000;"></i> Kelola Lokasi Outlet</h2>
    </div>

    <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <a href="{{ route('owner.lokasi.form') }}" class="btn btn-add" style="margin:0;">
            <i class="ph-bold ph-plus"></i> Tambah Lokasi
        </a>
        <a href="{{ route('owner.outlet_karyawan') }}" class="btn" style="background:#2d3436; color:white;">
            <i class="ph-bold ph-user-gear"></i> Aktivasi & Akun Karyawan
        </a>
    </div>

    <div class="card">
        <table class="native-table" style="min-width:950px;">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Nama Outlet</th>
                    <th>Info Akun</th>
                    <th>Koordinat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($lokasi->isEmpty())
                    <tr><td colspan="5" style="text-align:center; color:#999; padding:30px;">Belum ada lokasi.</td></tr>
                @endif

                @foreach ($lokasi as $row)
                    @php
                        // Tentukan warna lencana sesuai kategori lokasi.
                        $kategori = strtolower($row->kategori ?? 'cabang');
                        $badge = 'badge-cabang';
                        if ($kategori === 'pusat') $badge = 'badge-pusat';
                        if ($kategori === 'outlet') $badge = 'badge-outlet';
                        if ($kategori === 'mitra') $badge = 'badge-mitra';
                        $alamat = $row->alamat ?? '';
                        $coord = (! empty($row->latitude) && ! empty($row->longitude))
                            ? $row->latitude . ', ' . $row->longitude
                            : 'Belum diset';
                    @endphp
                    <tr>
                        <td><span class="badge {{ $badge }}">{{ $row->kategori }}</span></td>
                        <td>
                            <div style="font-weight:700;">{{ $row->nama }}</div>
                            <div style="font-size:12px; color:#777; max-width:260px; white-space:normal; line-height:1.35;">
                                {{ strlen($alamat) > 80 ? substr($alamat, 0, 80) . '...' : $alamat }}
                            </div>
                        </td>
                        <td>
                            @if (! empty($row->username_admin))
                                <div style="color:green; font-weight:700; font-size:13px;">User: {{ $row->username_admin }}</div>
                            @else
                                <div style="color:#b71c1c; font-size:12px; font-weight:700;">Akun belum aktif</div>
                            @endif
                            <div style="font-size:12px; color:#555; margin-top:4px;"><i class="ph-bold ph-phone"></i> {{ $row->telepon ?? '-' }}</div>
                        </td>
                        <td style="font-size:12px; font-family:monospace; color:#0066cc;">{{ $coord }}</td>
                        <td>
                            <div style="display:flex; gap:8px;">
                                <a href="{{ route('owner.lokasi.form', ['id' => $row->id]) }}" class="btn btn-edit" title="Edit">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </a>
                                <form action="{{ route('owner.lokasi.hapus', ['id' => $row->id]) }}" method="POST" class="form-hapus-lokasi">
                                    @csrf
                                    <button type="submit" class="btn btn-del" title="Hapus">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Konfirmasi hapus lokasi memakai SweetAlert2.
    document.querySelectorAll('.form-hapus-lokasi').forEach((form) => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus lokasi?',
                text: 'Data lokasi yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endsection
