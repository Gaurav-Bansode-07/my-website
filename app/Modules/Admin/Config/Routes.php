<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('admin', 'AdminController::blogs', [
    'namespace' => 'App\Modules\Admin\Controllers',
]);

$routes->get('admin/test', 'AdminController::test', [
    'namespace' => 'App\Modules\Admin\Controllers',
]);

$routes->get('admin/blogs', 'AdminController::blogs', [
    'namespace' => 'App\Modules\Admin\Controllers',
]);

$routes->get('admin/blogs/create', 'AdminController::create', [
    'namespace' => 'App\Modules\Admin\Controllers',
]);

$routes->post('admin/blogs/store', 'AdminController::store', [
    'namespace' => 'App\Modules\Admin\Controllers',
]);
$routes->get('admin/blogs/edit/(:num)', 'AdminController::edit/$1', [
    'namespace' => 'App\Modules\Admin\Controllers',
]);

$routes->post('admin/blogs/update/(:num)', 'AdminController::update/$1', [
    'namespace' => 'App\Modules\Admin\Controllers',
]);

$routes->get('admin/blogs/delete/(:num)', 'AdminController::delete/$1', [
    'namespace' => 'App\Modules\Admin\Controllers',
]);