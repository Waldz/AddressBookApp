<?php

use Application\Application;
use Auth\Service\AuthService;

try {
    /** @var Application $application */
    $application = require(__DIR__ . '/../init_application.php');
    $serviceManager = $application->getServiceManager();
    /** @var AuthService $authService */
    $authService = $serviceManager->get('Auth.AuthService');

    $authService->unauthenticate();
    header('Location: /');
    exit(0);

} catch (\Exception $e) {
    // In case of bootstrap error do not show blank screen.
    echo $e->getMessage()."<br />";
    echo $e->getTraceAsString();
}

$application->shutdown();
echo $application->debugGetOutput();
