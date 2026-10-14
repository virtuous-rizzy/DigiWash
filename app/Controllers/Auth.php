<?php

namespace App\Controllers;

class Auth extends BaseController
{
    /**
     * Halaman tujuan setelah login berdasarkan peran.
     */
    private function tujuan(string $peran): string
    {
        return match ($peran) {
            'admin' => '/admin/dashboard',
            'kurir' => '/kurir/dashboard',
            default => '/dashboard',
        };
    }

    /**
     * Jika sudah login, arahkan ke dashboard.
     */
    private function sudahMasuk()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(
                $this->tujuan((string) session()->get('role'))
            );
        }

        return null;
    }

    /**
     * Memeriksa apakah email sudah digunakan.
     */
    private function emailDipakai(string $email): bool
    {
        $db = \Config\Database::connect();

        foreach (['admin', 'kurir', 'pelanggan'] as $tabel) {
            if (
                $db->table($tabel)
                    ->where('email', $email)
                    ->countAllResults() > 0
            ) {
                return true;
            }
        }

        return false;
    }

    // ---------- Daftar pelanggan ----------

    public function daftar()
    {
        if ($r = $this->sudahMasuk()) {
            return $r;
        }

        return view('daftar_akun');
    }

    public function prosesDaftar()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'telp' => 'required|numeric|min_length[10]|max_length[20]',
            'email' => 'required|valid_email|max_length[100]',
            'password' => 'required|min_length[6]',
            'setuju' => 'required',
        ];

        $pesan = [
            'setuju' => [
                'required' => 'Setujui ketentuan layanan dan kebijakan privasi dulu.',
            ],
        ];

        if (!$this->validate($rules, $pesan)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = trim((string) $this->request->getPost('email'));

        try {
            $db = \Config\Database::connect();

            if ($this->emailDipakai($email)) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['Email sudah terdaftar.']);
            }

            $berhasil = $db->table('pelanggan')->insert([
                'nama' => trim((string) $this->request->getPost('nama')),
                'no_hp' => trim((string) $this->request->getPost('telp')),
                'alamat' => '',
                'email' => $email,
                'password' => password_hash(
                    (string) $this->request->getPost('password'),
                    PASSWORD_DEFAULT
                ),
            ]);

            if (!$berhasil) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['Pendaftaran gagal. Silakan coba lagi.']);
            }

        } catch (\Throwable $e) {
            log_message('error', 'Auth prosesDaftar DB Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'Database tidak tersedia. Pendaftaran gagal.',
                ]);
        }

        return redirect()->to('/masuk')
            ->with('success', 'Akun berhasil dibuat, silakan masuk.');
    }

    // ---------- Masuk admin, kurir, pelanggan ----------

    public function masuk()
    {
        if ($r = $this->sudahMasuk()) {
            return $r;
        }

        return view('masuk_akun');
    }

    public function prosesMasuk()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = trim((string) $this->request->getPost('email'));
        $sandi = (string) $this->request->getPost('password');

        try {
            $db = \Config\Database::connect();

            $kunci = [
                'admin' => 'id_admin',
                'kurir' => 'id_kurir',
                'pelanggan' => 'id_pelanggan',
            ];

            foreach ($kunci as $peran => $kolomId) {
                $akun = $db->table($peran)
                    ->where('email', $email)
                    ->get()
                    ->getRowArray();

                if (!$akun) {
                    continue;
                }

                // Password wajib cocok dengan hash di database.
                if (
                    !isset($akun['password']) ||
                    !password_verify($sandi, (string) $akun['password'])
                ) {
                    return redirect()->back()
                        ->withInput()
                        ->with('errors', [
                            'Email atau kata sandi salah.',
                        ]);
                }

                // Tolak akun yang dinonaktifkan.
                if (($akun['status'] ?? 'aktif') === 'nonaktif') {
                    return redirect()->back()
                        ->withInput()
                        ->with('errors', [
                            'Akun dinonaktifkan. Hubungi admin.',
                        ]);
                }

                // Pastikan ID akun tersedia.
                if (!isset($akun[$kolomId])) {
                    log_message(
                        'error',
                        'Auth: Kolom ID akun tidak ditemukan pada tabel ' . $peran
                    );

                    return redirect()->back()
                        ->withInput()
                        ->with('errors', [
                            'Data akun tidak valid. Hubungi admin.',
                        ]);
                }

                // Login hanya untuk akun valid di database.
                session()->regenerate();
                session()->remove('foto');

                session()->set([
                    'logged_in' => true,
                    'role' => $peran,
                    'peran' => $peran,
                    'user_id' => (int) $akun[$kolomId],
                    'nama' => (string) $akun['nama'],
                    'email' => (string) $akun['email'],
                ]);

                return redirect()->to($this->tujuan($peran));
            }

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Auth prosesMasuk DB Error: ' . $e->getMessage()
            );

            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'Database bermasalah. Login gagal, silakan coba lagi.',
                ]);
        }

        // Akun tidak ditemukan di seluruh tabel.
        return redirect()->back()
            ->withInput()
            ->with('errors', [
                'Email atau kata sandi salah.',
            ]);
    }

    // ---------- Keluar ----------

    public function keluar()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}