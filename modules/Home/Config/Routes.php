<?php

namespace Modules\Home\Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */


$routes->group('home', function($routes){
    $routes->get('/', 'Modules\Home\Controllers\Home::index');
});
