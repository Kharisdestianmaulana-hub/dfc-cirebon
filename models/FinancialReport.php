<?php
require_once __DIR__ . '/BaseModel.php';
class FinancialReport extends BaseModel
{
 public function save(string $outlet,string $date,float $gross,float $expense,string $note): bool { $net=$gross-$expense;$exists=$this->fetchOne('SELECT id FROM laporan_keuangan WHERE tanggal = :tanggal AND kode_outlet = :outlet',['tanggal'=>$date,'outlet'=>$outlet]); if($exists) return $this->execute('UPDATE laporan_keuangan SET omzet_kotor = :gross, pengeluaran = :expense, omzet_bersih = :net, catatan = :note WHERE id = :id',['gross'=>$gross,'expense'=>$expense,'net'=>$net,'note'=>$note,'id'=>$exists['id']]); return $this->execute('INSERT INTO laporan_keuangan (tanggal,kode_outlet,omzet_kotor,pengeluaran,omzet_bersih,catatan) VALUES (:tanggal,:outlet,:gross,:expense,:net,:note)',['tanggal'=>$date,'outlet'=>$outlet,'gross'=>$gross,'expense'=>$expense,'net'=>$net,'note'=>$note]); }
 public function latest(string $outlet,int $limit=5): array { $limit=max(1,$limit); return $this->fetchAll("SELECT * FROM laporan_keuangan WHERE kode_outlet = :outlet ORDER BY tanggal DESC LIMIT $limit",['outlet'=>$outlet]); }
 public function chart(string $outlet): array { return array_reverse($this->latest($outlet,7)); }
}
