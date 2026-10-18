<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class KelolaAdm extends BaseController
{
    private const PERAN = ['admin', 'kurir', 'pelanggan'];

    private const TABEL = ['admin' => 'admin', 'kurir' => 'kurir', 'pelanggan' => 'pelanggan'];
    private const KUNCI = ['admin' => 'id_admin', 'kurir' => 'id_kurir', 'pelanggan' => 'id_pelanggan'];

    private function idSaya(): int
    {
        return (int) session()->get('user_id');
    }

    private function ambil(string $peran): array
    {
        $db = \Config\Database::connect();

        $sql = match ($peran) {
            'admin'     => $db->table('admin')->select('id_admin AS id, nama, noHP AS no_hp, email, status'),
            'kurir'     => $db->table('kurir')->select('id_kurir AS id, nama, no_hp, email, status'),
            default     => $db->table('pelanggan')->select('id_pelanggan AS id, nama, no_hp, email, status'),
        };

        return array_map(
            static fn (array $b): array => $b + ['peran' => $peran],
            $sql->get()->getResultArray()
        );
    }

    public function index()
    {
        $filter = (string) $this->request->getGet('peran');
        if (! in_array($filter, self::PERAN, true)) {
            $filter = 'semua';
        }
        $q = trim((string) $this->request->getGet('q'));

        $daftar = [];
        $jumlah = [];
        foreach (self::PERAN as $p) {
            $semua       = $this->ambil($p);
            $jumlah[$p]  = count($semua);
            if ($filter === 'semua' || $filter === $p) {
                $daftar = array_merge($daftar, $semua);
            }
        }

        if ($q !== '') {
            $daftar = array_values(array_filter($daftar, static function (array $d) use ($q): bool {
                foreach (['nama', 'no_hp', 'email'] as $k) {
                    if (stripos((string) $d[$k], $q) !== false) {
                        return true;
                    }
                }
                return false;
            }));
        }

        $urut = ['admin' => 0, 'kurir' => 1, 'pelanggan' => 2];
        usort($daftar, static fn (array $a, array $b): int =>
            [$urut[$a['peran']], strtolower((string) $a['nama'])] <=> [$urut[$b['peran']], strtolower((string) $b['nama'])]
        );

        return view('admin/kelola_adm', [
            'daftar' => $daftar,
            'filter' => $filter,
            'q'      => $q,
            'jumlah' => $jumlah,
            'idSaya' => $this->idSaya(),
            'aktif'  => 'beranda',
        ]);
    }

    public function tambah()
    {
        return view('admin/kelola_form_adm', ['aktif' => 'beranda']);
    }

    public function simpan()
    {
        $rules = [
            'peran'    => 'required|in_list[admin,kurir]',
            'nama'     => 'required|min_length[3]|max_length[100]',
            'no_hp'    => 'required|numeric|min_length[10]|max_length[20]',
            'email'    => 'required|valid_email|max_length[100]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $peran = (string) $this->request->getPost('peran');
        $nama  = trim((string) $this->request->getPost('nama'));
        $hp    = trim((string) $this->request->getPost('no_hp'));
        $email = trim((string) $this->request->getPost('email'));
        $sandi = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);

        $db = \Config\Database::connect();

        // Email dan nomor telepon harus unik di semua peran
        $kolom = [
            'admin'     => ['email', 'noHP'],
            'kurir'     => ['email', 'no_hp'],
            'pelanggan' => ['email', 'no_hp'],
        ];
        foreach ($kolom as $tabel => [$kEmail, $kHp]) {
            if ($db->table($tabel)->where($kEmail, $email)->countAllResults() > 0) {
                return redirect()->back()->withInput()->with('errors', ['Email sudah dipakai akun lain.']);
            }
            if ($db->table($tabel)->where($kHp, $hp)->countAllResults() > 0) {
                return redirect()->back()->withInput()->with('errors', ['Nomor telepon sudah dipakai akun lain.']);
            }
        }

        if ($peran === 'admin') {
            $db->table('admin')->insert([
                'nama' => $nama, 'noHP' => $hp, 'email' => $email, 'password' => $sandi, 'status' => 'aktif',
            ]);
        } else {
            $db->table('kurir')->insert([
                'nama' => $nama, 'no_hp' => $hp, 'email' => $email, 'password' => $sandi, 'status' => 'tersedia',
            ]);
        }

        return redirect()->to('/admin/kelola')->with('success', 'Akun ' . $peran . ' baru berhasil ditambahkan.');
    }

    public function status(string $peran, int $id)
    {
        if (! in_array($peran, self::PERAN, true)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $db    = \Config\Database::connect();
        $tabel = self::TABEL[$peran];
        $kunci = self::KUNCI[$peran];
        $akun  = $db->table($tabel)->where($kunci, $id)->get()->getRowArray();

        if (! $akun) {
            throw PageNotFoundException::forPageNotFound();
        }

        $sekarang = (string) ($akun['status'] ?? 'aktif');

        if ($peran === 'admin' && $id === $this->idSaya()) {
            return redirect()->back()->with('errors', ['Kamu tidak bisa menonaktifkan akunmu sendiri.']);
        }

        if ($peran === 'kurir' && $sekarang === 'bertugas') {
            return redirect()->back()->with('errors', ['Kurir ' . $akun['nama'] . ' sedang bertugas dan belum bisa dinonaktifkan.']);
        }

        if ($sekarang === 'nonaktif') {
            $baru = $peran === 'kurir' ? 'tersedia' : 'aktif';
        } else {
            $baru = 'nonaktif';
        }

        $db->table($tabel)->where($kunci, $id)->update(['status' => $baru]);

        return redirect()->back()->with('success', 'Akun ' . $akun['nama'] . ' sekarang ' . $baru . '.');
    }
}