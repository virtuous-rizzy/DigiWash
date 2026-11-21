<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table         = 'pelanggan';
    protected $primaryKey    = 'id_pelanggan';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nama', 'no_hp', 'alamat', 'alamat_alternatif',
        'email', 'password', 'tanggal_lahir', 'jenis_kelamin',
<<<<<<< HEAD
=======
        'foto', 'status',
>>>>>>> origin/digiwash-semoga-final
    ];
}