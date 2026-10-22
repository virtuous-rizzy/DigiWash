<?php

if (! function_exists('foto_profil')) {
    /** URL foto profil akun yang sedang login (pelanggan, admin, atau kurir), atau null kalau belum punya. */
    function foto_profil(): ?string
    {
        $session = session();
        $nama    = (string) $session->get('foto');

        if ($nama === '') {
            $peran = (string) ($session->get('role') ?? $session->get('peran'));
            $peta  = [
                'pelanggan' => ['pelanggan', 'id_pelanggan'],
                'admin'     => ['admin', 'id_admin'],
                'kurir'     => ['kurir', 'id_kurir'],
            ];

            if (! isset($peta[$peran])) {
                return null;
            }

            [$tabel, $kunci] = $peta[$peran];

            $baris = \Config\Database::connect()
                ->table($tabel)
                ->select('foto')
                ->where($kunci, (int) $session->get('user_id'))
                ->get()
                ->getRowArray();

            $nama = (string) ($baris['foto'] ?? '');

            if ($nama !== '') {
                $session->set('foto', $nama);
            }
        }

        if ($nama === '' || ! is_file(FCPATH . 'uploads/profil/' . $nama)) {
            return null;
        }

        return base_url('uploads/profil/' . $nama);
    }
<<<<<<< HEAD
=======
}

if (! function_exists('url_foto_profil')) {
    /**
     * URL foto profil berdasarkan nama file di uploads/profil, atau null jika tidak ada / tidak valid.
     */
    function url_foto_profil(?string $namaFile): ?string
    {
        if (empty($namaFile)) {
            return null;
        }

        $namaFile = trim($namaFile);

        // Jika sudah merupakan URL lengkap
        if (str_starts_with($namaFile, 'http://') || str_starts_with($namaFile, 'https://')) {
            return $namaFile;
        }

        // Cek path uploads/profil/ (lokasi utama upload profil di DigiWash)
        if (is_file(FCPATH . 'uploads/profil/' . $namaFile)) {
            return base_url('uploads/profil/' . $namaFile);
        }

        // Fallback jika disimpan di folder uploads/ langsung
        if (is_file(FCPATH . 'uploads/' . $namaFile)) {
            return base_url('uploads/' . $namaFile);
        }

        // Fallback jika disimpan dengan subfolder di dalam nama file
        if (is_file(FCPATH . $namaFile)) {
            return base_url($namaFile);
        }

        return null;
    }
>>>>>>> origin/digiwash-semoga-final
}