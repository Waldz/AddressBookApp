<?php

namespace Application\Service;

/**
 * Service manager - container for services
 *
 * @package Application
 * @subpackage Service
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class ServiceManager
{

    /**
     * Array of service factories
     *
     * @var \Closure[]
     */
    private $factories = [];

    /**
     * Array of loaded service instances
     *
     * @var array
     */
    private $servicesLoaded = [];

    /**
     * Loads service
     *
     * @param string $serviceName Parameter name
     *
     * @return mixed Parameter value
     */
    public function get($serviceName)
    {
        if (!isset($this->servicesLoaded[$serviceName])) {
            $this->servicesLoaded[$serviceName] = $this->factoryByCallback($serviceName);
        }

        return $this->servicesLoaded[$serviceName];
    }

    /**
     * Change parameter value
     *
     * @param string $serviceName
     * @param \Closure $callback
     *
     * @return $this
     */
    public function registerFactoryCallback($serviceName, \Closure $callback)
    {
        $this->factories[$serviceName] = $callback;

        return $this;
    }

    /**
     * @param $serviceName
     * @return mixed
     */
    protected function factoryByCallback($serviceName)
    {
        if (!isset($this->factories[$serviceName])) {
            throw new \InvalidArgumentException('I dont know such service: '.$serviceName);
        }

        return $this->factories[$serviceName]($this);
    }

}
