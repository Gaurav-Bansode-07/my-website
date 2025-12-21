<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('admin', ['namespace' => 'App\Modules\Admin\Controllers'], function($routes) {
    $routes->get('', 'AdminController::blogs');
    $routes->get('blogs', 'AdminController::blogs');
    $routes->get('blogs/create', 'AdminController::create');
    $routes->post('blogs/store', 'AdminController::store');
    $routes->get('blogs/edit/(:num)', 'AdminController::edit/$1');
    $routes->post('blogs/update/(:num)', 'AdminController::update/$1');
    $routes->get('blogs/delete/(:num)', 'AdminController::delete/$1');
});
