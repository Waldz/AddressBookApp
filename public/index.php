<?php

use Application\Application;

try {
    /** @var Application $application */
    $application = require(__DIR__ . '/../init_application.php');

    /** @var \AddressBook\Service\ContactRepository $repository */
    $repository = $application->getServiceManager()->get('AddressBook.ContactRepository');
    var_dump($repository->contactGet(1));
    var_dump($repository->contactList([1]));

} catch (\Exception $e) {
    // In case of bootstrap error do not show blank screen.
    echo $e->getMessage()."<br />";
    echo $e->getTraceAsString();
}

$application->shutdown();
echo $application->debugGetOutput();
