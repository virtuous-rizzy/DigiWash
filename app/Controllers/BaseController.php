<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Helper yang otomatis dimuat di semua controller.
     * Letaknya di dalam class, di luar fungsi.
     */
<<<<<<< HEAD
    protected $helpers = ['url', 'form'];
=======
    protected $helpers = ['url', 'form', 'foto'];
>>>>>>> origin/digiwash-semoga-final

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Jangan hapus baris ini
        parent::initController($request, $response, $logger);
    }
}