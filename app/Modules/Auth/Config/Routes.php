<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('login', 'AuthController::login', [
    'namespace' => 'App\Modules\Auth\Controllers'
]);

$routes->post('login', 'AuthController::attemptLogin', [
    'namespace' => 'App\Modules\Auth\Controllers'
]);

$routes->get('logout', 'AuthController::logout', [
    'namespace' => 'App\Modules\Auth\Controllers',
]);

