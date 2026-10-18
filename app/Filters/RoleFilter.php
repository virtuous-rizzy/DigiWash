<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/masuk')->with('errors', ['Silakan masuk terlebih dahulu.']);
        }

        $role = session()->get('role');

        if (! in_array($role, $arguments ?? [], true)) {
            $tujuan = match ($role) {
                'admin' => '/admin/dashboard',
                'kurir' => '/kurir/dashboard',
                default => '/dashboard',
            };

            return redirect()->to($tujuan);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}