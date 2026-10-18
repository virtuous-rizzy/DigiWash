<?php
namespace App\Models;
use CodeIgniter\Model;

class KritikSaranModel extends Model
{
    protected $table            = 'kritik_saran';
    protected $primaryKey       = 'id_kritik_saran';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_pelanggan',
        'id_admin',
        'no_pesanan',
        'rating',
        'isi_pesan',
        'jenis',
        'status',
        'tanggal',
        'created_at',
        'updated_at'
    ];

    // Timestamps
    protected $useTimestamps    = false; // Gunakan kolom tanggal sesuai ERD

    /**
     * Ambil semua kritik beserta data pelanggan & admin penindak lanjut (untuk Admin & Kurir)
     */
    public function getKritikDenganPelanggan($status = 'Belum ditindaklanjuti', $keyword = null)
    {
        $builder = $this->select('kritik_saran.*, pelanggan.nama, pelanggan.foto, admin.nama AS nama_admin')
                        ->join('pelanggan', 'pelanggan.id_pelanggan = kritik_saran.id_pelanggan')
                        ->join('admin', 'admin.id_admin = kritik_saran.id_admin', 'left');
        
        // Filter berdasarkan Status (Belum ditindaklanjuti, Dalam Proses, Selesai)
        if ($status) {
            $builder->where('kritik_saran.status', $status);
        }

        // Filter berdasarkan kolom Pencarian
        if ($keyword) {
            $builder->groupStart()
                    ->like('pelanggan.nama', $keyword)
                    ->orLike('kritik_saran.isi_pesan', $keyword)
                    ->orLike('kritik_saran.no_pesanan', $keyword)
                    ->orLike('kritik_saran.jenis', $keyword)
                    ->groupEnd();
        }

        return $builder->orderBy('kritik_saran.tanggal', 'DESC')->findAll();
    }

    /**
     * Ambil kritik milik pelanggan tertentu beserta nama admin yang menindaklanjuti (untuk Pelanggan)
     */
    public function getKritikByPelanggan($id_pelanggan, $status = null, $keyword = null)
    {
        $builder = $this->select('kritik_saran.*, admin.nama AS nama_admin')
                        ->join('admin', 'admin.id_admin = kritik_saran.id_admin', 'left')
                        ->where('kritik_saran.id_pelanggan', $id_pelanggan);

        if ($status) {
            $builder->where('kritik_saran.status', $status);
        }

        if ($keyword) {
            $builder->groupStart()
                    ->like('kritik_saran.isi_pesan', $keyword)
                    ->orLike('kritik_saran.no_pesanan', $keyword)
                    ->orLike('kritik_saran.jenis', $keyword)
                    ->groupEnd();
        }

        return $builder->orderBy('kritik_saran.tanggal', 'DESC')->findAll();
    }
}