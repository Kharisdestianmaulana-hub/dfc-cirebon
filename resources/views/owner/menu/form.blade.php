@extends('layouts.owner')

@section('konten')
@php
    $item = $menuItem;
    $isEdit = $mode === 'edit';
    $fileName = basename((string) ($item->gambar ?? ''));
    $imageUrl = $fileName ? asset('assets/img/owner/' . rawurlencode($fileName)) : '';
@endphp
<div class="content">
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="ph-bold ph-list"></i>
    </button>

    <div class="page-header">
        <h2><i class="ph-fill ph-hamburger" style="color:#ff0000;"></i> {{ $isEdit ? 'Edit' : 'Tambah' }} Menu</h2>
        <a href="{{ route('owner.menu.index') }}" class="owner-btn owner-btn-muted">
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="owner-card owner-form">
        <form method="POST" action="{{ route('owner.menu.simpan') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $item->id ?? '' }}">
            <input type="hidden" name="gambar_lama" value="{{ $item->gambar ?? '' }}">

            <label>Nama Menu</label>
            <input type="text" name="nama" value="{{ $item->nama ?? '' }}" placeholder="Cth: Ayam Geprek" required>

            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="{{ $item->harga ?? '' }}" placeholder="Cth: 15000" min="1" required>

            <label>Foto Menu</label>
            @if ($imageUrl)
                <img src="{{ $imageUrl }}" class="current-image" alt="Foto menu saat ini">
            @elseif ($isEdit && ! empty($item->gambar))
                <p style="color:#b71c1c; font-size:13px; margin-top:0;">File gambar lama tidak ditemukan: {{ $item->gambar }}</p>
            @endif
            <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            <small style="display:block; margin-top:-12px; margin-bottom:20px; color:#777;">Kosongkan jika tidak ingin mengganti foto.</small>

            <div class="owner-actions" style="margin-bottom:0;">
                <button type="submit" class="owner-btn owner-btn-primary">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan Data
                </button>
                <a href="{{ route('owner.menu.index') }}" class="owner-btn owner-btn-muted">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
