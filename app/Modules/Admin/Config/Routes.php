<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->group('admin', [
    'namespace' => 'App\Modules\Admin\Controllers',
    'filter' => 'group:admin'
], function ($routes) {
    // === BLOG ROUTES (already exist) ===
    $routes->get('', 'AdminController::blogs'); 
    $routes->get('blogs', 'AdminController::blogs');
    $routes->get('blogs/create', 'AdminController::create');
    $routes->post('blogs/store', 'AdminController::store');
    $routes->get('blogs/edit/(:num)', 'AdminController::edit/$1');
    $routes->post('blogs/update/(:num)', 'AdminController::update/$1');
    $routes->get('blogs/delete/(:num)', 'AdminController::delete/$1');

	// === VIDEO ROUTES (add these) ===
	$routes->get('videos', 'AdminController::videos');                    // List
	$routes->get('videos/create', 'AdminController::videoCreate');        // Create form
	$routes->post('videos/store', 'AdminController::videoStore');         // Store
	$routes->get('videos/edit/(:num)', 'AdminController::videoEdit/$1');  // Edit form
	$routes->post('videos/update/(:num)', 'AdminController::videoUpdate/$1'); // Update
	$routes->get('videos/delete/(:num)', 'AdminController::videoDelete/$1'); // Delete

});