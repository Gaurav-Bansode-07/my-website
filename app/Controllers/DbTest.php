<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DbTest extends Controller
{
    public function index()
    {
        try {
            $db = \Config\Database::connect();
            $db->connect();

            return '✅ Database connected successfully';
        } catch (\Throwable $e) {
            return '❌ DB Error: ' . $e->getMessage();
        }
    }
}
