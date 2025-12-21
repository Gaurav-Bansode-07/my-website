<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// === AUTH ROUTES (Simple Login System) ===
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// === ADMIN ROUTES (Protected) ===
$routes->group('admin', function($routes) {
    $routes->get('', 'AdminController::blogs', ['namespace' => 'App\Modules\Admin\Controllers']);
    $routes->get('test', 'AdminController::test', ['namespace' => 'App\Modules\Admin\Controllers']);
    $routes->get('blogs', 'AdminController::blogs', ['namespace' => 'App\Modules\Admin\Controllers']);
    $routes->get('blogs/create', 'AdminController::create', ['namespace' => 'App\Modules\Admin\Controllers']);
    $routes->post('blogs/store', 'AdminController::store', ['namespace' => 'App\Modules\Admin\Controllers']);
    $routes->get('blogs/edit/(:num)', 'AdminController::edit/$1', ['namespace' => 'App\Modules\Admin\Controllers']);
    $routes->post('blogs/update/(:num)', 'AdminController::update/$1', ['namespace' => 'App\Modules\Admin\Controllers']);
    $routes->get('blogs/delete/(:num)', 'AdminController::delete/$1', ['namespace' => 'App\Modules\Admin\Controllers']);
});