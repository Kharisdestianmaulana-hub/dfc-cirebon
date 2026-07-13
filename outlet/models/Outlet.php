<?php
require_once __DIR__ . '/BaseModel.php';

class Outlet extends BaseModel
{
    private function stockColumn(string $outlet): string { return 'stok_' . preg_replace('/[^a-zA-Z0-9_]/', '', $outlet); }
    public function status(string $outlet): string { $row=$this->fetchOne('SELECT status FROM pengaturan_toko WHERE kode_outlet = :outlet',['outlet'=>$outlet]); return $row['status'] ?? 'tutup'; }
    public function setStatus(string $outlet, string $status): void { $status=in_array($status,['buka','tutup'],true)?$status:'tutup'; $this->execute('UPDATE pengaturan_toko SET status = :status WHERE kode_outlet = :outlet',['status'=>$status,'outlet'=>$outlet]); }
    public function menus(): array { return $this->fetchAll('SELECT * FROM menu'); }
    public function updateStock(string $outlet, int $id, int $stock): ?string { $menu=$this->fetchOne('SELECT nama FROM menu WHERE id = :id',['id'=>$id]); if(!$menu)return null; $column=$this->stockColumn($outlet); $this->execute("UPDATE menu SET `$column` = :stock WHERE id = :id",['stock'=>max(0,$stock),'id'=>$id]); return $menu['nama']; }
    public function recordSale(string $outlet, int $id, int $qty, string $note): ?string { $qty=max(1,$qty);$menu=$this->fetchOne('SELECT nama, harga FROM menu WHERE id = :id',['id'=>$id]);if(!$menu)return null;$column=$this->stockColumn($outlet);$price=(float)$menu['harga'];$this->db->beginTransaction();try{$this->execute('INSERT INTO penjualan_harian (tanggal,waktu,kode_outlet,nama_menu,qty,catatan,harga_satuan,subtotal) VALUES (:tanggal,:waktu,:outlet,:menu,:qty,:note,:price,:subtotal)',['tanggal'=>date('Y-m-d'),'waktu'=>date('H:i:s'),'outlet'=>$outlet,'menu'=>$menu['nama'],'qty'=>$qty,'note'=>$note,'price'=>$price,'subtotal'=>$price*$qty]);$this->execute("UPDATE menu SET `$column` = GREATEST(0, `$column` - :qty) WHERE id = :id",['qty'=>$qty,'id'=>$id]);$this->db->commit();return $menu['nama'];}catch(Throwable $exception){$this->db->rollBack();throw $exception;} }
    public function salesHistory(string $outlet): array { return $this->fetchAll('SELECT * FROM penjualan_harian WHERE kode_outlet = :outlet AND tanggal = :tanggal ORDER BY id DESC',['outlet'=>$outlet,'tanggal'=>date('Y-m-d')]); }
    public function requestStock(string $outletName,string $item,int $qty,string $unit): bool { return $this->execute('INSERT INTO request_stok (nama_outlet,nama_barang,jumlah,satuan,status) VALUES (:outlet,:item,:qty,:unit,:status)',['outlet'=>$outletName,'item'=>trim($item),'qty'=>max(1,$qty),'unit'=>trim($unit),'status'=>'Pending']); }
    public function requestHistory(string $outletName): array { return $this->fetchAll('SELECT * FROM request_stok WHERE nama_outlet = :outlet ORDER BY id DESC LIMIT 5',['outlet'=>$outletName]); }
}
