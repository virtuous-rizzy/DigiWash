<?php

namespace App\Models;

use CodeIgniter\Model;

class AlamatModel extends Model
{
    protected $table         = 'alamat_pelanggan';
    protected $primaryKey    = 'id_alamat';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id_pelanggan', 'label', 'nama_penerima', 'no_hp',
        'alamat_lengkap', 'catatan_kurir', 'is_default',
    ];

    public function daftar(int $idPelanggan): array
    {
        return $this->where('id_pelanggan', $idPelanggan)
            ->orderBy('is_default', 'DESC')
            ->orderBy('id_alamat', 'DESC')
            ->findAll();
    }

    public function milik(int $idAlamat, int $idPelanggan): ?array
    {
        $hasil = $this->where('id_alamat', $idAlamat)
            ->where('id_pelanggan', $idPelanggan)
            ->first();

        return is_array($hasil) ? $hasil : null;
    }

    public function utama(int $idPelanggan): ?array
    {
        $hasil = $this->where('id_pelanggan', $idPelanggan)
            ->where('is_default', 1)
            ->first();

        return is_array($hasil) ? $hasil : null;
    }

    public function setDefault(int $idAlamat, int $idPelanggan): void
    {
        $db = \Config\Database::connect();

        $db->transStart();
        $this->builder()->where('id_pelanggan', $idPelanggan)->update(['is_default' => 0]);
        $this->builder()->where('id_alamat', $idAlamat)->where('id_pelanggan', $idPelanggan)->update(['is_default' => 1]);
        $db->transComplete();
    }
}