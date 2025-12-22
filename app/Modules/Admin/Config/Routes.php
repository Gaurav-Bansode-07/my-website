<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('admin', [
    'namespace' => 'App\Modules\Admin\Controllers',
    'filter'    => 'group:admin'
], function ($routes) {
    // Map both /admin and /admin/blogs to the blog list
    $routes->get('', 'AdminController::blogs');        // /admin        → blog list
    $routes->get('blogs', 'AdminController::blogs');   // /admin/blogs  → blog list

    $routes->get('blogs/create', 'AdminController::create');
    $routes->post('blogs/store', 'AdminController::store');
    $routes->get('blogs/edit/(:num)', 'AdminController::edit/$1');
    $routes->post('blogs/update/(:num)', 'AdminController::update/$1');
    $routes->get('blogs/delete/(:num)', 'AdminController::delete/$1');
});