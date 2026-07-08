<?php
class Lokasi extends Controller {
    public function index() {
        $data['judul'] = 'Lokasi - DFC Cirebon';
        $data['lokasi'] = $this->model('Lokasi_model')->getLokasi();
        
        // Prepare data for Maps (filtering empty coordinates)
        $lokasi_data = [];
        foreach($data['lokasi'] as $row) {
            if (!empty($row['latitude']) && !empty($row['longitude'])) {
                $lokasi_data[] = [
                    'category' => $row['kategori'],
                    'name' => $row['nama'],
                    'address' => $row['alamat'],
                    'lat' => $row['latitude'],
                    'lng' => $row['longitude']
                ];
            }
        }
        $data['lokasi_json'] = json_encode($lokasi_data);

        $this->view('templates/header', $data);
        $this->view('lokasi/index', $data);
        $this->view('templates/footer');
    }
}
