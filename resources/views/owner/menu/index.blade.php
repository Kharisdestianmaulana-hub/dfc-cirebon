@extends('layouts.owner')

@section('konten')
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-hamburger" style="color:#ff0000;"></i> Daftar Menu DFC</h2>
    </div>

    <a href="{{ route('owner.menu.form') }}" class="btn btn-add">
        <i class="ph-bold ph-plus"></i> Tambah Menu Baru
    </a>

    <div class="card">
        <table class="native-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($menu->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align:center; color:#999; padding:30px;">Belum ada data menu.</td>
                    </tr>
                @endif

                @php $no = 1; @endphp
                @foreach ($menu as $item)
                    @php
                        $fileName = basename((string) ($item->gambar ?? ''));
                        $imgSrc = $fileName
                            ? asset('assets/img/owner/' . rawurlencode($fileName))
                            : 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22150%22%20height%3D%22150%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20150%20150%22%3E%3Crect%20width%3D%22150%22%20height%3D%22150%22%20fill%3D%22%23EEEEEE%22%2F%3E%3Ctext%20x%3D%2242%22%20y%3D%2280%22%20fill%3D%22%23AAAAAA%22%20font-size%3D%2213%22%20font-family%3D%22Arial%22%3ENo%20Image%3C%2Ftext%3E%3C%2Fsvg%3E';
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><img src="{{ $imgSrc }}" class="img-thumb" alt="{{ $item->nama }}"></td>
                        <td style="font-weight:700;">{{ $item->nama }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                <a href="{{ route('owner.menu.form', ['id' => $item->id]) }}" class="btn btn-edit">
                                    <i class="ph-bold ph-pencil-simple"></i> Edit
                                </a>
                                <form action="{{ route('owner.menu.hapus', ['id' => $item->id]) }}" method="POST" class="form-hapus-menu" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-del">
                                        <i class="ph-bold ph-trash"></i> Hapus
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
    // Konfirmasi hapus menu memakai SweetAlert2.
    document.querySelectorAll('.form-hapus-menu').forEach((form) => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin hapus menu?',
                text: 'Data yang dihapus tidak bisa dikembalikan.',
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
