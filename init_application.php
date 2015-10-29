<?php

use Application\Application;
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
    'database',
    function (ServiceManager $serviceManager) {
        /** @var Config $config */
        $config = $serviceManager->get('config');
        $configDb = $config['database'];

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

return $application;
