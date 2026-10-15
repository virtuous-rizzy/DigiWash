<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_pelanggan', 'id_layanan', 'id_admin',
        'tanggal', 'berat', 'alamat_pengambilan', 'alamat_pengantaran',
        'status_pesanan', 'catatan', 'detail_satuan',
    ];

    /**
     * Ambil semua pesanan milik pelanggan tertentu
     * beserta nama layanan dan status pembayaran.
     */
    public function milikPelanggan(int $idPelanggan): array
    {
        return $this->db->table('pesanan p')
            ->select([
                'p.id_pesanan',
                'p.tanggal',
                'p.berat',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'l.nama_layanan',
                'l.harga_per_kg',
                'l.harga_flat',
                'l.jenis_layanan',
                'pb.status_bayar',
                'pb.metode',
                // Hitung total: prioritaskan total_bayar di tabel pembayaran, jika tidak ada hitung dari harga layanan
                'IF(pb.total_bayar IS NOT NULL AND pb.total_bayar > 0, pb.total_bayar, IF(l.harga_flat > 0, l.harga_flat, l.harga_per_kg * p.berat)) AS total_harga',
            ])
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->where('p.id_pelanggan', $idPelanggan)
            ->orderBy('p.id_pesanan', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil pesanan berdasarkan status_pesanan (filter tab).
     * $status bisa berupa string tunggal atau array.
     */
    public function milikPelangganByStatus(int $idPelanggan, $status): array
    {
        $builder = $this->db->table('pesanan p')
            ->select([
                'p.id_pesanan',
                'p.tanggal',
                'p.berat',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'l.nama_layanan',
                'l.harga_per_kg',
                'l.harga_flat',
                'l.jenis_layanan',
                'pb.status_bayar',
                'pb.metode',
                'IF(pb.total_bayar IS NOT NULL AND pb.total_bayar > 0, pb.total_bayar, IF(l.harga_flat > 0, l.harga_flat, l.harga_per_kg * p.berat)) AS total_harga',
            ])
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->where('p.id_pelanggan', $idPelanggan);

        if (is_array($status)) {
            $builder->whereIn('p.status_pesanan', $status);
        } else {
            $builder->where('p.status_pesanan', $status);
        }

        return $builder->orderBy('p.id_pesanan', 'DESC')->get()->getResultArray();
    }

    /** Satu pesanan lengkap berdasarkan id (dan pastikan milik pelanggan). */
    public function detailPesanan(int $idPesanan, int $idPelanggan): ?array
    {
        $result = $this->db->table('pesanan p')
            ->select([
                'p.*',
                'l.nama_layanan', 'l.harga_per_kg', 'l.harga_flat', 'l.jenis_layanan', 'l.estimasi_hari',
                'pb.id_pembayaran', 'pb.metode', 'pb.status_bayar',
                'IF(pb.total_bayar IS NOT NULL AND pb.total_bayar > 0, pb.total_bayar, IF(l.harga_flat > 0, l.harga_flat, l.harga_per_kg * p.berat)) AS total_harga',
            ])
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->where('p.id_pesanan',   $idPesanan)
            ->where('p.id_pelanggan', $idPelanggan)
            ->get()
            ->getRowArray();

        return is_array($result) ? $result : null;
    }

    /** Hitung pesanan aktif (bukan selesai/dibatalkan) milik pelanggan. */
    public function jumlahAktif(int $idPelanggan): int
    {
        return (int) $this->db->table('pesanan')
            ->where('id_pelanggan', $idPelanggan)
            ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
            ->countAllResults();
    }
}
