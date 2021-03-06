<?php

use AddressBook\Model\Contact;
use AddressBook\Transformer\ContactTransformer;
use AddressBook\Service\ContactRepository;
use AddressBook\Validator\ContactValidator;
use Application\Application;
use Auth\Service\AuthService;

/**
 * @param ContactRepository $contactRepository
 * @return Contact
 */
function fetchContact($contactRepository)
{
    if (empty($_GET['id'])) {
        responseError(404, 'No contact id given');
    }

    $contact = $contactRepository->contactGet($_GET['id']);
    $contactRepository->fetchSupervisors([$contact]);
    $contactRepository->fetchSupervisedPersons([$contact]);

    if (!isset($contact)) {
        responseError(404, 'No such contact id: '.$_GET['id']);
    }

    return $contact;
}

function requestParseJson() {
    $requestBody = file_get_contents('php://input');
    $requestJson = json_decode($requestBody, true);
    if (json_last_error()) {
        responseError(422, 'Invalid JSON given: '.json_last_error());
    }

    return $requestJson;
}

function responseError($statusCode, $errorMessage) {
    http_response_code($statusCode);
    header('Content-Type: application/json');

    echo json_encode([
        'message' => $errorMessage,
    ]);
    exit(0);
}

function responseJson($statusCode, $jsonData) {
    http_response_code($statusCode);
    header('Content-Type: application/json');

    echo json_encode($jsonData);
    exit(0);
}

try {
    /** @var Application $application */
    $application = require(__DIR__ . '/../init_application.php');
    /** @var ContactRepository $contactRepository */
    $contactRepository = $application->getServiceManager()->get('AddressBook.ContactRepository');
    /** @var AuthService $authService */
    $authService = $serviceManager->get('Auth.AuthService');

    if (!$authService->isAuthenticated()) {
        responseError(403, 'Sorry, no rights to go here');
    }

    $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
    switch ($requestMethod) {
        case 'post':
            $contact = new Contact();
            $contact = ContactTransformer::fromRequest(requestParseJson(), $contact);

            $errors = ContactValidator::validate($contact);
            if (count($errors)>0) {
                responseJson(400, $errors);
            }

            $contactRepository->contactSave($contact);
            $contactRepository->fetchSupervisors([$contact]);

            responseJson(200, ContactTransformer::toTransient($contact));
            break;

        case 'put':
            $contact = fetchContact($contactRepository);
            $contact = ContactTransformer::fromRequest(requestParseJson(), $contact);

            $errors = ContactValidator::validate($contact);
            if (count($errors)>0) {
                responseJson(400, $errors);
            }

            $contactRepository->contactSave($contact);
            $contactRepository->fetchSupervisors([$contact]);

            responseJson(200, ContactTransformer::toTransient($contact));
            break;

        case 'delete':
            $contact = fetchContact($contactRepository);
            $contactRepository->contactDelete($contact);

            responseJson(200, []);
            break;

        case 'get':
        default:
            $contact = fetchContact($contactRepository);

            responseJson(200, ContactTransformer::toTransient($contact));
            break;
    }


} catch (\Exception $e) {
    // In case of bootstrap error do not show blank screen.
    responseJson(500, [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
}

$application->shutdown();
