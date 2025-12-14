<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Throwable;

class DbTest extends Controller
{
    public function index()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            $db = \Config\Database::connect();
            $db->initialize();
            return '✅ Database connected successfully';
        } catch (Throwable $e) {
            return nl2br(
                "❌ ERROR TYPE: " . get_class($e) . "\n\n" .
                "❌ MESSAGE: " . $e->getMessage()
            );
        }
    }
}
