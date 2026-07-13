<?php
require_once __DIR__ . '/BaseModel.php';
class Order extends BaseModel
{
    public function activeForOutlet(string $outlet): array
    {
        return $this->fetchAll(
            "SELECT * FROM transaksi_pesanan WHERE kode_outlet LIKE :outlet AND status_pesanan != 'Selesai' ORDER BY waktu_pesan ASC",
            ['outlet' => '%' . $outlet . '%']
        );
    }

    public function updateStatus(string $id, string $status): void
    {
        $allowed = ['Sedang Disiapkan', 'Siap Diambil', 'Selesai'];
        if (!in_array($status, $allowed, true)) {
            return;
        }

        $this->execute(
            'UPDATE transaksi_pesanan SET status_pesanan = :status WHERE order_id = :id',
            ['status' => $status, 'id' => $id]
        );
    }
}
