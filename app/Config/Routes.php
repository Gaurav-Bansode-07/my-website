<?php

namespace Config;

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

/*
 * Router Setup
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
 * Load system routes first
 */
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * Load HMVC module routes
 */

// Auth module
if (file_exists(APPPATH . 'Modules/Auth/Config/Routes.php')) {
    require APPPATH . 'Modules/Auth/Config/Routes.php';
}

// Home module
if (file_exists(APPPATH . 'Modules/Home/Config/Routes.php')) {
    require APPPATH . 'Modules/Home/Config/Routes.php';
}

// Blog module
if (file_exists(APPPATH . 'Modules/Blog/Config/Routes.php')) {
    require APPPATH . 'Modules/Blog/Config/Routes.php';
}
