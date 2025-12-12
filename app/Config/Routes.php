<?php

namespace Modules\Home\Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('home', ['namespace' => 'Modules\Home\Controllers'], function($routes){
    $routes->get('/', 'Home::index');
});
