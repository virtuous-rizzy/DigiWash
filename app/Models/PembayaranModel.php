<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table         = 'pembayaran';
    protected $primaryKey    = 'id_pembayaran';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id_pesanan', 'id_admin', 'metode', 'status_bayar',
        'bukti_bayar', 'total_bayar', 'tanggal_bayar',
    ];

    /** Ambil pembayaran berdasarkan id_pesanan. */
    public function cariByPesanan(int $idPesanan): ?array
    {
        $result = $this->where('id_pesanan', $idPesanan)->first();
        return is_array($result) ? $result : null;
    }

    /**
     * Ambil semua transaksi untuk sisi Admin,
     * lengkap dengan relasi ke pesanan, pelanggan, dan layanan.
     */
    public function semuaTransaksi(?string $status = null, ?string $keyword = null): array
    {
        $builder = $this->db->table('pembayaran pb')
            ->select([
                'pb.id_pembayaran',
                'pb.id_pesanan',
                'pb.id_admin',
                'pb.metode',
                'pb.status_bayar',
                'pb.bukti_bayar',
                'pb.total_bayar',
                'pb.tanggal_bayar',
                'ps.tanggal AS tanggal_pesanan',
                'ps.berat',
                'ps.status_pesanan',
                'ps.alamat_pengambilan',
                'ps.catatan',
                'pl.id_pelanggan',
                'pl.nama AS nama_pelanggan',
                'pl.foto AS foto_pelanggan',
                'pl.no_hp AS no_hp_pelanggan',
                'l.nama_layanan',
                'l.jenis_layanan',
                'l.harga_per_kg',
                'l.harga_flat',
            ])
            ->join('pesanan ps',   'ps.id_pesanan = pb.id_pesanan', 'inner')
            ->join('pelanggan pl', 'pl.id_pelanggan = ps.id_pelanggan', 'left')
            ->join('layanan l',    'l.id_layanan = ps.id_layanan', 'left');

        if ($status === 'dibatalkan') {
            $builder->groupStart()
                ->like('ps.catatan', 'dibatalkan')
                ->orGroupStart()
                    ->whereIn('ps.status_pesanan', ['dibatalkan'])
                    ->where('pb.id_admin IS NULL')
                ->groupEnd()
            ->groupEnd();
        } elseif ($status === 'ditolak') {
            $builder->where('pb.status_bayar', 'gagal')
                ->groupStart()
                    ->where('pb.id_admin IS NOT NULL')
                    ->orLike('ps.catatan', 'ditolak')
                ->groupEnd()
                ->notLike('ps.catatan', 'dibatalkan');
        } elseif ($status === 'gagal') {
            $builder->where('pb.status_bayar', 'gagal');
        } elseif (! empty($status) && $status !== 'semua') {
            $builder->where('pb.status_bayar', $status);
        }

        if (! empty($keyword)) {
            $cleanKw = trim($keyword);
            $numericId = preg_replace('/\D/', '', $cleanKw);
            $builder->groupStart()
                ->like('pl.nama', $cleanKw)
                ->orLike('pb.metode', $cleanKw)
                ->orLike('l.nama_layanan', $cleanKw);

            if ($numericId !== '') {
                $builder->orWhere('pb.id_pesanan', (int) $numericId)
                        ->orWhere('pb.id_pembayaran', (int) $numericId);
            }
            $builder->groupEnd();
        }

        return $builder->orderBy('pb.id_pembayaran', 'DESC')->get()->getResultArray();
    }

    /**
     * Verifikasi transaksi oleh Admin:
     * Status bayar -> 'lunas'
     * Status pesanan -> 'dikonfirmasi' (atau 'dicuci')
     */
    public function verifikasiTransaksi(int $idPembayaran, int $idAdmin): bool
    {
        $transaksi = $this->find($idPembayaran);
        if (! $transaksi) {
            return false;
        }

        // Transaksi yang sudah lunas atau sudah ditolak tidak boleh diubah lagi
        if ($transaksi['status_bayar'] === 'lunas' || $transaksi['status_bayar'] === 'gagal') {
            return false;
        }

        $this->update($idPembayaran, [
            'status_bayar' => 'lunas',
            'id_admin'     => $idAdmin,
        ]);

        if (! empty($transaksi['id_pesanan'])) {
            $this->db->table('pesanan')
                ->where('id_pesanan', (int) $transaksi['id_pesanan'])
                ->update([
                    'status_pesanan' => 'dikonfirmasi',
                    'id_admin'       => $idAdmin,
                ]);
        }

        return true;
    }

    /**
     * Tolak transaksi oleh Admin:
     * Status bayar -> 'gagal'
     * Status pesanan -> 'dibatalkan'
     */
    public function tolakTransaksi(int $idPembayaran, int $idAdmin): bool
    {
        $transaksi = $this->find($idPembayaran);
        if (! $transaksi) {
            return false;
        }

        // Transaksi yang sudah ditolak atau sudah lunas tidak boleh diubah lagi
        if ($transaksi['status_bayar'] === 'gagal' || $transaksi['status_bayar'] === 'lunas') {
            return false;
        }

        $this->update($idPembayaran, [
            'status_bayar' => 'gagal',
            'id_admin'     => $idAdmin,
        ]);

        if (! empty($transaksi['id_pesanan'])) {
            $idPesananInt = (int) $transaksi['id_pesanan'];
            $currentPsn = $this->db->table('pesanan')->where('id_pesanan', $idPesananInt)->get()->getRowArray();
            $catatanPsn = trim((string)($currentPsn['catatan'] ?? ''));
            if (! str_contains(strtolower($catatanPsn), 'ditolak')) {
                $catatanPsn = trim($catatanPsn . ' [Ditolak oleh Admin]');
            }

            $this->db->table('pesanan')
                ->where('id_pesanan', $idPesananInt)
                ->update([
                    'status_pesanan' => 'dibatalkan',
                    'id_admin'       => $idAdmin,
                    'catatan'        => $catatanPsn,
                ]);

            $dist = $this->db->table('distribusi')->where('id_pesanan', $idPesananInt)->get()->getRowArray();
            if ($dist) {
                if (! empty($dist['id_kurir'])) {
                    $this->db->table('kurir')->where('id_kurir', (int) $dist['id_kurir'])->update(['status' => 'tersedia']);
                }
                $this->db->table('distribusi')->where('id_pesanan', $idPesananInt)->update([
                    'id_kurir'     => null,
                    'status_antar' => 'menunggu_kurir',
                ]);
            }
        }

        return true;
    }
}
