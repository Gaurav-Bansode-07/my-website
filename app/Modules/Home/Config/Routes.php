<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'HomeController::index', [
    'namespace' => 'App\Modules\Home\Controllers',
]);

$routes->get('home', 'HomeController::index', [
    'namespace' => 'App\Modules\Home\Controllers',
]);

$routes->get('blog/(:segment)', 'BlogController::show/$1', [
    'namespace' => 'App\Modules\Blog\Controllers',
]);
