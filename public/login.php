<?php

use Application\Application;
use Application\View\ViewRenderer;
use Auth\Service\AuthService;

try {
    /** @var Application $application */
    $application = require(__DIR__ . '/../init_application.php');
    $serviceManager = $application->getServiceManager();
    /** @var ViewRenderer $renderer */
    $renderer = $serviceManager->get('view.renderer');
    /** @var AuthService $authService */
    $authService = $serviceManager->get('Auth.AuthService');

    $errors = [];


    $outputBody = $renderer->renderTemplate('login-form', 'Auth', [
        'inputEmail' => isset($_POST['email']) ? $_POST['email'] : '',
        'errors' => $errors,
    ]);
    $output = $renderer->renderTemplate('layout', 'Application', [
        'pageActive' => 'login',
        'blockBody' => $outputBody,
    ]);
    echo $output;

} catch (\Exception $e) {
    // In case of bootstrap error do not show blank screen.
    echo $e->getMessage()."<br />";
    echo $e->getTraceAsString();
}

$application->shutdown();
echo $application->debugGetOutput();
