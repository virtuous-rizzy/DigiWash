<?php

namespace App\Controllers;

class Beranda extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(match (session()->get('role')) {
                'admin' => '/admin/dashboard',
                'kurir' => '/kurir/dashboard',
                default => '/dashboard',
            });
        }

        return view('beranda');
    }
}