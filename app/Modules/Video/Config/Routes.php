<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('videos', 'VideoController::index', [
    'namespace' => 'App\Modules\Video\Controllers',
]);

$routes->get('videos/(:segment)', 'VideoController::show/$1', [
    'namespace' => 'App\Modules\Video\Controllers',
]);