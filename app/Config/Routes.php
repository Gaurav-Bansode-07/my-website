<?php

/** @var \CodeIgniter\Router\RouteCollection $routes */

$routes->group('auth', [
    'namespace' => 'App\Modules\Auth\Controllers',
], static function ($routes) {
    $routes->get('/', 'AuthController::index');
});
