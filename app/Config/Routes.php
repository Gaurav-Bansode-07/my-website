<?php

namespace Config;

use CodeIgniter\Config\Services;

/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */
$routes = Services::routes();

/*
 * Router Setup (minimal, stable)
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->get('login', '\CodeIgniter\Shield\Controllers\LoginController::loginView');
$routes->post('login', '\CodeIgniter\Shield\Controllers\LoginController::loginAction');
$routes->get('logout', '\CodeIgniter\Shield\Controllers\LoginController::logoutAction');
$routes->get('register', '\CodeIgniter\Shield\Controllers\RegisterController::registerView');
$routes->post('register', '\CodeIgniter\Shield\Controllers\RegisterController::registerAction');
/*
 * 1️⃣ Load system routes FIRST (mandatory)
 */
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * 2️⃣ Load HMVC module routes ONLY
 * App routes file NEVER defines URLs
 */
$modules = [
    'Auth',
    'Home',
    'Blog',
    'Admin', // ✅ ADD THIS
];


foreach ($modules as $module) {
    $path = APPPATH . "Modules/{$module}/Config/Routes.php";
    if (file_exists($path)) {
        require $path;
    }
}
