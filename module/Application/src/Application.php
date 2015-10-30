<?php

namespace Application;

use Application\Model\Config;
use Application\Service\ServiceManager;

/**
 * Class responsible for application logic:
 *  - systems starting and finishing
 *  - resources bootstrapping (config, DB, logger, etc.)
 *  - resources retrieving
 *
 * @package Application
 * @subpackage Service
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class Application
{

    /**
     * Application configuration
     *
     * @var Config
     */
    private $config;

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * Time system was started
     *
     * @var float
     */
    private $timeStart;

    /**
     * System's debug messages written to one string
     *
     * @var string
     */
    private $debugOutput = '';

    /**
     * Starts the system
     *
     * @throws \UnexpectedValueException
     * @return Application
     */
    public function bootstrap()
    {
        if (isset($this->timeStart)) {
            throw new \UnexpectedValueException('Please, dont bootstrap system several times');
        }
        $this->timeStart = microtime(true);

        $this->loadApplicationConfig();
        $this->configurePhpEnvironment();
        $this->configurePhpSession();

        $this->debug('System started');

        return $this;
    }

    /**
     * Read all configuration files
     */
    protected function loadApplicationConfig()
    {
        $configFileRules = [
            'config/autoload/{,*.}{global,local}.php'
        ];

        $config = new Config([]);
        $config->setReadOnly(false);
        foreach ($configFileRules as $fileRule) {
            foreach (glob($fileRule, GLOB_BRACE) as $configFile) {
                $config->mergeParams(include($configFile));
            }
        }
        $config->setReadOnly(true);

        $this->setConfig($config);
    }

    protected function configurePhpEnvironment()
    {
        // Error logging
        if ($this->debugIsOn()) {
            ini_set('display_errors', true);
        } else {
            ini_set('display_errors', false);
        }
    }

    protected function configurePhpSession()
    {
        session_start();
    }

    /**
     * Finishes the system
     *
     * @return Application
     */
    public function shutdown()
    {
        $this->debug('System finished');

        return $this;
    }

    /**
     * Inject application configuration
     *
     * @param Config $config
     * @return Application
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Retrieves wanted configuration value
     *
     * @param string $paramName Configuration name
     *
     * @return mixed Configuration value
     */
    public function config($paramName)
    {
        return $this->getConfig()->get($paramName);
    }

    /**
     * Retrieves serviceManager.
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        if (!isset($this->serviceManager)) {
            $this->serviceManager = new ServiceManager();
        }

        return $this->serviceManager;
    }

    /**
     * Sets serviceManager.
     *
     * @param ServiceManager $serviceManager
     * @return Application
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * Prints given message or variable to debug output
     *
     * @param mixed $message Message or variable
     */
    public function debug($message) {
        if (!$this->debugIsOn()) {
            return;
        }

        if (is_string($message)) {
            // Do nothing
        } elseif ($message instanceof \Exception) {
            $message = $message->__toString();
        } else {
            $message = '<pre>'.var_export($message, true).'</pre>';
        }

        $this->debugOutput .= sprintf(
            '%s %01.6fs %s<br/>',
            date('Y-m-d H:i:s'),
            microtime(true) - $this->timeStart,
            $message
        );
    }

    /**
     * Check if debug should be turned on in current enviroment
     *
     * @return boolean Is debuging on
     */
    public function debugIsOn() {
        return $this->config('debug')
            && isset($_SERVER['REMOTE_ADDR'])
            && in_array($_SERVER['REMOTE_ADDR'], $this->config('debug_ips'));
    }

    /**
     * Gets all debug messages written to one string
     *
     * @return string
     */
    public function debugGetOutput() {
        return $this->debugOutput;
    }

}
