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
}