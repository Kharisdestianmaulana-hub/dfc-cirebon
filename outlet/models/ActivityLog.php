<?php
require_once __DIR__ . '/BaseModel.php';

class ActivityLog extends BaseModel
{
    public function add(string $actor, string $action, string $detail): void
    {
        $this->execute(
            'INSERT INTO log_aktivitas (waktu, pelaku, aksi, detail) VALUES (:waktu, :pelaku, :aksi, :detail)',
            [
                'waktu' => date('Y-m-d H:i:s'),
                'pelaku' => $actor,
                'aksi' => $action,
                'detail' => $detail,
            ]
        );
    }
}
