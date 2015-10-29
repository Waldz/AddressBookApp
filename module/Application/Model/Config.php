<?php

namespace Application\Model;

/**
 * Class Config - container for parameters
 *
 * @package Application
 * @subpackage Model
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class Config implements \ArrayAccess
{
    /**
     * Parameters array
     *
     * @var array
     */
    private $params = [];

    /**
     * @var bool
     */
    private $readOnly = true;

    public function __construct($params=[])
    {
        $this->setParams($params);
    }

    /**
     * @param boolean $readOnly
     * @return $this
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * Retrieves parameter value
     *
     * @param string $paramName Parameter name
     *
     * @return mixed Parameter value
     */
    public function get($paramName)
    {
        return $this->offsetGet($paramName);
    }

    /**
     * Change parameter value
     *
     * @param string $paramName Parameter name
     * @param string $paramValue Parameter value
     *
     * @return $this
     */
    public function set($paramName, $paramValue)
    {
        $this->offsetSet($paramName, $paramValue);
        return $this;
    }

    /**
     * Delete parameter value
     *
     * @param string $paramName Parameter name
     *
     * @return $this
     */
    public function delete($paramName)
    {
        $this->offsetUnset($paramName);
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function mergeParams($params)
    {
        $this->params = $this->mergeRecursively($this->params, $params);

        return $this;
    }

    /**
     * Merge two arrays recursively.
     * Value of the second array overwrites value in the first array.
     *
     * @param array $destination
     * @param array $source
     *
     * @return array
     */
    protected function mergeRecursively(array $destination, array $source)
    {
        foreach ($source as $key => $value) {
            if (array_key_exists($key, $destination)) {
                if (is_int($key)) {
                    $destination[] = $value;
                } elseif (is_array($value) && is_array($destination[$key])) {
                    $destination[$key] = static::mergeRecursively($destination[$key], $value);
                } else {
                    $destination[$key] = $value;
                }
            } else {
                $destination[$key] = $value;
            }
        }

        return $destination;
    }

    /**
     * Set values of all params
     *
     * @param array $params
     */
    protected function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Set value of one param
     *
     * @param string $param
     * @param string $value
     */
    protected function setParam($param, $value)
    {
        $this->params[$param] = $value;
    }

    /**
     * Dros value of one param
     *
     * @param string $param
     */
    protected function unsetParam($param)
    {
        if(isset($this->params[$param])) {
            unset($this->params[$param]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->params[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (isset($this->params[$offset])) {
            return $this->params[$offset];
        } else {
            throw new \InvalidArgumentException('I dont know such param: '.$offset);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if(!$this->isReadOnly()) {
            $this->setParam($offset, $value);
        } else {
            throw new \InvalidArgumentException('I dont allow to change configurations');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if(!$this->isReadOnly()) {
            $this->unsetParam($offset);
        } else {
            throw new \InvalidArgumentException('I dont allow to delete configurations');
        }
    }

}
