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

    /**
     * Ambil daftar tugas kurir lengkap dengan data pesanan dan pelanggan
     */
    public function tugasKurir(int $idKurir, string $tab = 'semua'): array
    {
        $builder = $this->db->table('distribusi d')
            ->select([
                'd.id_distribusi',
                'd.id_pesanan',
                'd.id_kurir',
                'd.status_antar',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'p.tanggal',
                'p.berat',
                'pl.nama AS nama_pelanggan',
                'pl.no_hp AS no_hp_pelanggan',
                'l.nama_layanan',
            ])
            ->join('pesanan p',    'p.id_pesanan = d.id_pesanan', 'inner')
            ->join('pelanggan pl', 'pl.id_pelanggan = p.id_pelanggan', 'left')
            ->join('layanan l',    'l.id_layanan = p.id_layanan', 'left')
            ->where('d.id_kurir',  $idKurir);

        $daftar = $builder->orderBy('d.id_distribusi', 'DESC')->get()->getResultArray();

        // Tentukan tipe tugas (penjemputan atau pengantaran)
        $tugas = [];
        foreach ($daftar as $d) {
            $stPesanan = (string) ($d['status_pesanan'] ?? 'menunggu');
            $tipe = in_array($stPesanan, ['menunggu', 'dikonfirmasi', 'dijemput'], true) ? 'penjemputan' : 'pengantaran';

            if ($tab === 'penjemputan' && $tipe !== 'penjemputan') {
                continue;
            }
            if ($tab === 'pengantaran' && $tipe !== 'pengantaran') {
                continue;
            }

            $d['tipe'] = $tipe;
            $tugas[] = $d;
        }

        return $tugas;
    }

    /**
     * Hitung ringkasan tugas aktif dan selesai milik kurir
     */
    public function hitungRingkasanKurir(int $idKurir): array
    {
        $semua = $this->db->table('distribusi d')
            ->select('d.status_antar, p.status_pesanan')
            ->join('pesanan p', 'p.id_pesanan = d.id_pesanan', 'left')
            ->where('d.id_kurir', $idKurir)
            ->get()->getResultArray();

        $aktif   = 0;
        $selesai = 0;
        $jemput  = 0;
        $antar   = 0;

        foreach ($semua as $s) {
            if (($s['status_pesanan'] ?? '') === 'selesai' || ($s['status_antar'] ?? '') === 'terkirim') {
                $selesai++;
            } else {
                $aktif++;
                if (in_array($s['status_pesanan'] ?? '', ['menunggu', 'dikonfirmasi', 'dijemput'], true)) {
                    $jemput++;
                } else {
                    $antar++;
                }
            }
        }

        return [
            'aktif'   => $aktif,
            'selesai' => $selesai,
            'jemput'  => $jemput,
            'antar'   => $antar,
        ];
    }
}

