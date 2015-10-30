<?php

use AddressBook\Service\ContactRepository;
use Application\Application;
use Application\Db\Driver\DatabaseDriver;
use Application\Db\Driver\MysqlDriver;
use Application\Model\Config;
use Application\Service\ServiceManager;

// Init autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $loader = include __DIR__ . '/vendor/autoload.php';
}

// Init application
chdir(__DIR__);
$application = new Application();
$application->bootstrap();


$serviceManager = $application->getServiceManager();
$serviceManager->registerFactoryCallback(
    'config',
    function () use($application) {
        return $application->getConfig();
    }
);

$serviceManager->registerFactoryCallback(
    'database.config',
    function (ServiceManager $serviceManager) {
        /** @var Config $config */
        $config = $serviceManager->get('config');
        return $config['database'];
    }
);

$serviceManager->registerFactoryCallback(
    'database.driver',
    function (ServiceManager $serviceManager) {
        /** @var Config $config */
        $configDb = $serviceManager->get('database.config');

        $db = new MysqlDriver();
        $db->connect(
            $configDb['db_user'],
            $configDb['db_password'],
            $configDb['db_host'],
            $configDb['db_port']
        );
        $db->selectDatabase($configDb['db_name']);
        $db->selectCharset($configDb['db_charset']);

        return $db;
    }
);

$serviceManager->registerFactoryCallback(
    'AddressBook.ContactRepository',
    function (ServiceManager $serviceManager) {
        /** @var DatabaseDriver $db */
        $db = $serviceManager->get('database.driver');

        return new ContactRepository($db);
    }
);

return $application;
