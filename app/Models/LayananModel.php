<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananModel extends Model
{
    protected $table         = 'layanan';
    protected $primaryKey    = 'id_layanan';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nama_layanan', 'jenis_layanan', 'harga_per_kg',
        'harga_flat', 'estimasi_hari', 'is_active', 'deskripsi', 'ikon',
    ];

    /** Ambil semua layanan yang aktif. */
    public function aktif(): array
    {
        return $this->where('is_active', 1)->findAll();
    }

    /** Ambil layanan berdasarkan ID numerik atau substring nama (misal: 'Reguler', 'Express'). */
    public function cariByIdOrNama($idOrNama): ?array
    {
        if (is_numeric($idOrNama)) {
            $hasil = $this->find((int) $idOrNama);
            if ($hasil) return $hasil;
        }

        // Cari berdasarkan kemiripan nama
        $hasil = $this->like('nama_layanan', (string) $idOrNama)->first();
        if ($hasil) return $hasil;

        // Fallback default
        return $this->first();
    }
}
