<div class="container mt-4 mb-5">
    <div class="row mb-3">
        <div class="col-md-8">
            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-history me-2" style="color: #e60023;"></i>Riwayat Perubahan Stok</h4>
            <p class="text-muted small">Laporan jejak rekam pembaruan kuantitas barang</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="<?= BASEURL; ?>/gudang" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-3 shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            
            <?php if(empty($data['riwayat'])) : ?>
                <div class="text-center p-5 text-muted">
                    <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                    <h5>Belum ada data riwayat.</h5>
                    <p class="small">Riwayat akan muncul setelah Anda memperbarui stok barang.</p>
                </div>
            <?php else : ?>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th scope="col" class="ps-4">WAKTU / TANGGAL</th>
                            <th scope="col">NAMA BARANG</th>
                            <th scope="col">DETAIL PERUBAHAN</th>
                            <th scope="col" class="pe-4">ADMIN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['riwayat'] as $row) : 
                            $selisih = $row['selisih'];
                            if ($selisih > 0) {
                                $tanda = "+";
                                $badgeClass = "bg-success bg-opacity-10 text-success border border-success border-opacity-25";
                                $icon = "fa-arrow-up";
                            } else {
                                $tanda = "";
                                $badgeClass = "bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25";
                                $icon = "fa-arrow-down";
                            }
                        ?>
                        <tr>
                            <td class="ps-4 text-muted small">
                                <i class="far fa-clock me-1"></i> <?= $row['waktu']; ?>
                            </td>
                            <td>
                                <span class="fw-bold text-dark"><?= $row['nama_barang']; ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted text-decoration-line-through small"><?= $row['stok_lama']; ?></span>
                                    <i class="fas fa-long-arrow-alt-right text-muted mx-1"></i>
                                    <span class="fw-bold fs-6"><?= $row['stok_baru']; ?></span>
                                    
                                    <span class="badge rounded-pill <?= $badgeClass; ?> ms-2">
                                        <i class="fas <?= $icon; ?> me-1"></i> <?= $tanda . $selisih; ?>
                                    </span>
                                </div>
                            </td>
                            <td class="pe-4 text-muted">
                                <i class="fas fa-user-tag me-1 text-secondary"></i> <?= $row['admin']; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php endif; ?>
            
        </div>
    </div>
</div>
