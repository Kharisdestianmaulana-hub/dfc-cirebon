<?php
class Kemitraan extends Controller {
    public function index() {
        $data['judul'] = 'Pendaftaran Mitra DFC Cirebon';
        $this->view('kemitraan/index', $data);
    }

    public function kirim() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['kirim_mitra'])) {
            header('Location: ' . BASEURL . '/kemitraan');
            exit;
        }

        $whatsapp = trim($_POST['whatsapp'] ?? '');
        if (substr($whatsapp, 0, 1) === '0') {
            $whatsapp = '62' . substr($whatsapp, 1);
        }

        $data = [
            'nama' => trim($_POST['nama'] ?? ''),
            'whatsapp' => $whatsapp,
            'lokasi' => trim($_POST['lokasi'] ?? ''),
            'pesan' => trim($_POST['pesan'] ?? '')
        ];

        if ($data['nama'] === '' || $data['whatsapp'] === '' || $data['lokasi'] === '') {
            $this->alertBack('Mohon lengkapi data pengajuan.');
        }

        if ($this->model('Kemitraan_model')->tambahPengajuan($data)) {
            $this->alertRedirect('Pengajuan Berhasil! Tim kami akan segera menghubungi Anda.', BASEURL . '/');
        }

        $this->alertBack('Gagal mengirim data.');
    }

    private function alertRedirect($message, $target) {
        $safeMessage = json_encode($message);
        $safeTarget = json_encode($target);
        echo "<script>alert($safeMessage); window.location.href = $safeTarget;</script>";
        exit;
    }

    private function alertBack($message) {
        $safeMessage = json_encode($message);
        echo "<script>alert($safeMessage); window.history.back();</script>";
        exit;
    }
}
