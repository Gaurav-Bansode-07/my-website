<?php

/** @var \CodeIgniter\Router\RouteCollection $routes */

// Auth module
if (file_exists(APPPATH . 'Modules/Auth/Config/Routes.php')) {
    require APPPATH . 'Modules/Auth/Config/Routes.php';
}

// Home module
if (file_exists(APPPATH . 'Modules/Home/Config/Routes.php')) {
    require APPPATH . 'Modules/Home/Config/Routes.php';
}

// Blog module (next)
if (file_exists(APPPATH . 'Modules/Blog/Config/Routes.php')) {
    require APPPATH . 'Modules/Blog/Config/Routes.php';
}
