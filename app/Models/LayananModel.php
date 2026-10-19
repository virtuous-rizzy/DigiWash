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

    /**
     * Ambil semua layanan untuk admin dengan filter status dan kata kunci
     */
    public function semuaLayanan(string $status = 'semua', string $q = ''): array
    {
        $builder = $this->builder();

        if ($status === 'aktif') {
            $builder->where('is_active', 1);
        } elseif ($status === 'nonaktif') {
            $builder->where('is_active', 0);
        }

        if (trim($q) !== '') {
            $builder->groupStart()
                ->like('nama_layanan', $q)
                ->orLike('deskripsi', $q)
                ->orLike('jenis_layanan', $q)
                ->groupEnd();
        }

        return $builder->orderBy('id_layanan', 'ASC')->get()->getResultArray();
    }

    /**
     * Hitung ringkasan status layanan
     */
    public function hitungStatus(): array
    {
        $db = $this->db;
        $semua = (int) $db->table('layanan')->countAllResults();
        $aktif = (int) $db->table('layanan')->where('is_active', 1)->countAllResults();
        $nonaktif = (int) $db->table('layanan')->where('is_active', 0)->countAllResults();

        return [
            'semua'    => $semua,
            'aktif'    => $aktif,
            'nonaktif' => $nonaktif,
        ];
    }

    /**
     * Cek apakah layanan sedang atau pernah digunakan pada riwayat pesanan
     */
    public function punyaPesanan(int $idLayanan): bool
    {
        return $this->db->table('pesanan')
            ->where('id_layanan', $idLayanan)
            ->countAllResults() > 0;
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
