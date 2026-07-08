<?php
class Profil extends Controller {
    public function index() {
        $data['judul'] = 'Tentang Kami - DFC Cirebon';
        $data['profil'] = $this->model('Profil_model')->getProfil();
        
        // Return default profile if empty
        if (!$data['profil']) {
            $data['profil'] = [
                'sejarah' => 'Dexpress Fried Chicken (DFC) adalah UMKM kuliner asli Cirebon...',
                'visi' => 'Menjadi brand Fried Chicken lokal nomor 1...',
                'misi' => 'Menyajikan produk ayam goreng yang Halal, Higienis, dan Hemat.'
            ];
        }

        $this->view('templates/header', $data);
        $this->view('profil/index', $data);
        $this->view('templates/footer');
    }
}
