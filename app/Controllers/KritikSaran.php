<?php

namespace App\Controllers;
use App\Models\KritikSaranModel;

class KritikSaran extends BaseController
{
    protected $kritikModel;

    public function __construct()
    {
        $this->kritikModel = new KritikSaranModel();
    }

    /**
     * Form Kritik & Saran — disesuaikan per role dan ERD
     */
    public function index()
    {
        $role = session()->get('role');
        $id_pelanggan = session()->get('id_pelanggan');

        $data = [];

        // Pelanggan: form kirim kritik + riwayat milik sendiri (order by tanggal)
        if ($role === 'pelanggan' && $id_pelanggan) {
            $data['riwayat'] = $this->kritikModel->getKritikByPelanggan($id_pelanggan);
            return view('pelanggan/kritik_plg', $data);
        }

        // Kurir: bisa kirim masukan & lihat masukan
        if ($role === 'kurir') {
            $id_kurir = session()->get('id_kurir');
            $data['riwayat'] = $this->kritikModel
                ->where('id_pelanggan', $id_kurir)
                ->orderBy('tanggal', 'DESC')
                ->findAll();
            return view('kurir/kritik_krr', $data);
        }

        // Admin: redirect langsung ke halaman kelola kritik & saran
        if ($role === 'admin') {
            return redirect()->to('/admin/kelola-kritik');
        }

        // Fallback: tampilkan form standalone
        return view('form_kritik');
    }

    /**
     * Simpan kritik/saran baru sesuai ERD (id_pelanggan, isi_pesan, tanggal)
     */
    public function simpan()
    {
        $role = session()->get('role');

        if ($role === 'pelanggan') {
            $id_pengirim = session()->get('id_pelanggan');
        } elseif ($role === 'kurir') {
            $id_pengirim = session()->get('id_kurir');
        } else {
            $id_pengirim = 1; // fallback simulasi
        }

        $pesanInput = $this->request->getPost('isi_pesan') ?? $this->request->getPost('pesan');

        $this->kritikModel->save([
            'id_pelanggan' => $id_pengirim,
            'id_admin'     => null, // Belum ditindaklanjuti oleh admin
            'no_pesanan'   => '#INV-' . date('dmy') . '-' . sprintf('%03d', rand(1, 999)),
            'rating'       => $this->request->getPost('rating') ?? 5,
            'isi_pesan'    => $pesanInput,
            'jenis'        => $this->request->getPost('jenis') ?? 'Saran',
            'status'       => 'Belum ditindaklanjuti',
            'tanggal'      => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Terima kasih! Kritik & saran Anda telah berhasil dikirim.');

        if ($role === 'kurir') {
            return redirect()->to('/kurir/kritik-saran');
        }
        return redirect()->to('/kritik-saran');
    }

    /**
     * Kelola Kritik & Saran
     * Admin: mengelola dan menindaklanjuti (isi id_admin)
     * Kurir: read-only
     * Pelanggan: riwayat kritik sendiri
     */
    public function kelola()
    {
        $role = session()->get('role');

        $status_aktif = $this->request->getGet('status') ?? 'Belum ditindaklanjuti';
        $keyword = $this->request->getGet('keyword');

        // Pelanggan: hanya riwayat sendiri
        if ($role === 'pelanggan') {
            $id_pelanggan = session()->get('id_pelanggan');
            $data = [
                'ulasan'      => $this->kritikModel->getKritikByPelanggan($id_pelanggan, $status_aktif, $keyword),
                'jml_belum'   => $this->kritikModel->where('id_pelanggan', $id_pelanggan)->where('status', 'Belum ditindaklanjuti')->countAllResults(),
                'jml_proses'  => $this->kritikModel->where('id_pelanggan', $id_pelanggan)->where('status', 'Dalam Proses')->countAllResults(),
                'jml_selesai' => $this->kritikModel->where('id_pelanggan', $id_pelanggan)->where('status', 'Selesai')->countAllResults(),
                'status_aktif'=> $status_aktif,
                'keyword'     => $keyword
            ];
            return view('pelanggan/kelola_kritik_plg', $data);
        }

        // Kurir: baca semua kritik
        if ($role === 'kurir') {
            $data = [
                'ulasan'      => $this->kritikModel->getKritikDenganPelanggan($status_aktif, $keyword),
                'jml_belum'   => $this->kritikModel->where('status', 'Belum ditindaklanjuti')->countAllResults(),
                'jml_proses'  => $this->kritikModel->where('status', 'Dalam Proses')->countAllResults(),
                'jml_selesai' => $this->kritikModel->where('status', 'Selesai')->countAllResults(),
                'status_aktif'=> $status_aktif,
                'keyword'     => $keyword
            ];
            return view('kurir/kelola_kritik_krr', $data);
        }

        // Admin: lihat semua + bisa menindaklanjuti
        $data = [
            'ulasan'      => $this->kritikModel->getKritikDenganPelanggan($status_aktif, $keyword),
            'jml_belum'   => $this->kritikModel->where('status', 'Belum ditindaklanjuti')->countAllResults(),
            'jml_proses'  => $this->kritikModel->where('status', 'Dalam Proses')->countAllResults(),
            'jml_selesai' => $this->kritikModel->where('status', 'Selesai')->countAllResults(),
            'status_aktif'=> $status_aktif,
            'keyword'     => $keyword
        ];
        return view('admin/kritik_adm', $data);
    }

    /**
     * Update status tindak lanjut kritik (Admin only)
     * Mengisi kolom id_admin sesuai admin yang sedang login
     */
    public function updateStatus($id)
    {
        $role = session()->get('role');

        if ($role !== 'admin') {
            return redirect()->back()->with('errors', ['Anda tidak memiliki izin untuk tindakan ini.']);
        }

        $status = $this->request->getPost('status');

        if (!in_array($status, ['Belum ditindaklanjuti', 'Dalam Proses', 'Selesai'])) {
            return redirect()->back()->with('errors', ['Status tidak valid.']);
        }

        $id_admin = session()->get('id_admin') ?? 1;

        // Update status dan simpan id_admin yang menindaklanjuti (sesuai relasi ERD)
        $this->kritikModel->update($id, [
            'status'   => $status,
            'id_admin' => $id_admin
        ]);

        session()->setFlashdata('success', 'Status kritik & saran berhasil diperbarui.');
        return redirect()->to('/admin/kelola-kritik?status=' . urlencode($status));
    }
}