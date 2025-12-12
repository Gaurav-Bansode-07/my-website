<?php

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPHPVersion = '8.0';

if (version_compare(PHP_VERSION, $minPHPVersion, '<')) {
    die("Your PHP version must be {$minPHPVersion} or higher to run CodeIgniter. Current version: " . PHP_VERSION);
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

/*
 *---------------------------------------------------------------
 * LOAD THE FRAMEWORK BOOTSTRAP FILE
 *---------------------------------------------------------------
 */

$pathsPath = FCPATH . '../app/Config/Paths.php';

if (! file_exists($pathsPath)) {
    die('Paths.php not found.');
}

require $pathsPath;

$paths = new Config\Paths();

/*
 *---------------------------------------------------------------
 * LOAD THE APP
 *---------------------------------------------------------------
 */

require $paths->systemDirectory . '/bootstrap.php';

exit(CodeIgniter\CodeIgniter::run());
