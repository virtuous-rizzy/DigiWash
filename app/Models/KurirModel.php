<?php

namespace App\Models;

use CodeIgniter\Model;

class KurirModel extends Model
{
    protected $table         = 'kurir';
    protected $primaryKey    = 'id_kurir';
    protected $returnType    = 'array';
<<<<<<< HEAD
    protected $allowedFields = ['nama', 'no_hp', 'status', 'email', 'password'];
=======
    protected $allowedFields = ['nama', 'no_hp', 'status', 'email', 'password', 'foto'];

    /** Ambil daftar kurir yang aktif / tersedia */
    public function daftarKurirAktif(): array
    {
        return $this->where('status !=', 'nonaktif')
            ->orderBy('nama', 'ASC')
            ->findAll();
    }
>>>>>>> origin/digiwash-semoga-final
}