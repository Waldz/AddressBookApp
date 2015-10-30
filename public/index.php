<?php

use Application\Application;

try {
    /** @var Application $application */
    $application = require(__DIR__ . '/../init_application.php');

    $db = $application->getServiceManager()->get('database.driver');

} catch (\Exception $e) {
    // In case of bootstrap error do not show blank screen.
    echo $e->getMessage()."<br />";
    echo $e->getTraceAsString();
}

$application->shutdown();
echo $application->debugGetOutput();
