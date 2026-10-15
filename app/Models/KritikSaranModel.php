<?php

namespace App\Models;

use CodeIgniter\Model;

class KritikSaranModel extends Model
{
    protected $table         = 'kritik_saran';
    protected $primaryKey    = 'id_kritik';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id_pelanggan', 'pesan', 'tanggal',
    ];
}
