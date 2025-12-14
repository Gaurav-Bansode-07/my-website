<?php

namespace App\Modules\Auth;

use CodeIgniter\Modules\Module as BaseModule;

class Module extends BaseModule
{
    public function registerRoutes($routes): void
    {
        $routes->group('auth', [
            'namespace' => 'App\Modules\Auth\Controllers',
        ], function ($routes) {
            $routes->get('/', 'AuthController::login');
            $routes->get('login', 'AuthController::login');
            $routes->get('register', 'AuthController::register');
        });
    }
}
