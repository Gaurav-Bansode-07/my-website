<?php

namespace Config;

use CodeIgniter\Shield\Config\Auth as ShieldAuth;

class Auth extends ShieldAuth
{
    public array $redirects = [
        'register'          => '/',
        'login'             => '/admin/blogs',   // After successful login
        'logout'            => 'login',
        'force_reset'       => '/',
        'permission_denied' => '/',               // Optional: keep or change
        'group_denied'      => 'login',          // ← THIS IS THE KEY FIX
    ];
}