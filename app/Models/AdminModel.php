<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table         = 'admin';
    protected $primaryKey    = 'id_admin';
    protected $returnType    = 'array';
    protected $allowedFields = ['nama', 'noHP', 'email', 'password'];
}