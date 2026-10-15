<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiModel extends Model
{
    protected $table         = 'distribusi';
    protected $primaryKey    = 'id_distribusi';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id_pesanan', 'id_kurir', 'jenis_kendaraan', 'biaya', 'status_antar',
    ];

    /** Ambil info distribusi dengan relasi kurir dan pesanan */
    public function cariByPesanan(int $idPesanan): ?array
    {
        return $this->db->table('distribusi d')
            ->select('d.*, k.nama as nama_kurir, k.no_hp as no_hp_kurir')
            ->join('kurir k', 'k.id_kurir = d.id_kurir', 'left')
            ->where('d.id_pesanan', $idPesanan)
            ->get()
            ->getRowArray();
    }
}
