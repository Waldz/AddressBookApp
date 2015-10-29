<?php

use Application\Application;

// Init autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $loader = include __DIR__ . '/vendor/autoload.php';
}

// Init application
chdir(__DIR__);
$application = new Application();
$application->bootstrap();

return $application;
