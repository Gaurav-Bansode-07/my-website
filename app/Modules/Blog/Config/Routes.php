<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('blog/(:segment)', 'BlogController::show/$1', [
    'namespace' => 'App\Modules\Blog\Controllers',
]);
