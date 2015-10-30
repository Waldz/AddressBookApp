<?php

use AddressBook\Service\ContactRepository;
use Application\Application;
use Application\View\ViewRenderer;

try {
    /** @var Application $application */
    $application = require(__DIR__ . '/../init_application.php');
    /** @var ViewRenderer $renderer */
    $renderer = $application->getServiceManager()->get('view.renderer');
    /** @var ContactRepository $contactRepository */
    $contactRepository = $application->getServiceManager()->get('AddressBook.ContactRepository');

    $masterContacts = $contactRepository->contactListWithoutSupervisor();
    $outputBody = $renderer->renderTemplate('contact-tree', 'AddressBook', [
        'contacts' => $contactRepository->fetchSupervisedPersons($masterContacts)
    ]);
    $output = $renderer->renderTemplate('layout', 'Application', [
        'pageActive' => 'address-book',
        'blockBody' => $outputBody
    ]);
    echo $output;

} catch (\Exception $e) {
    // In case of bootstrap error do not show blank screen.
    echo $e->getMessage()."<br />";
    echo $e->getTraceAsString();
}

$application->shutdown();
echo $application->debugGetOutput();
