<?php

namespace Application\Db\Model;

/**
 * Data model, responsibilities:
 *   - container for data
 *   - just to carry data
 *
 * @package Application
 * @subpackage Db
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class DatabaseModel
{

    /**
     * Array of fields that this model has.
     *
     * @var array
     */
    protected $structure = [];

    /**
     * Field values.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Constructor
     *
     * @param array $data OPTIONAL Data .
     * @throws \InvalidArgumentException
     */
    public function __construct(array $data = [])
    {
        $newData = [];
        if (isset($data)) {
            if (!is_array($data)) {
                throw new \InvalidArgumentException('Data must be an array');
            }
            $newData = $data;
        }
        $this->fillData($newData);
    }

    /**
     * Fills model with given data
     *
     * @param array $data
     */
    protected function fillData($data)
    {
        $newData = [];
        foreach ($this->getStructure() as $field) {
            $newData[$field] = isset($data[$field]) ? $data[$field] : null;
        }
        $this->data = $newData;
    }

    /**
     * Retrieve row field value
     *
     * @param string $field The user-specified field name.
     *
     * @return string The corresponding field value.
     * @throws \InvalidArgumentException if the $field is not a field in model.
     */
    protected function getField($field)
    {
        if (!in_array($field, $this->structure)) {
            throw new \InvalidArgumentException("Specified field \"$field\" is not in the model");
        }

        return $this->data[$field];
    }

    /**
     * Set field value
     *
     * @param string $field The field name.
     * @param mixed  $value The value for the property.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function setField($field, $value)
    {
        if (!in_array($field, $this->structure)) {
            throw new \InvalidArgumentException("Specified field \"$field\" is not in the model");
        }

        $this->data[$field] = $value;
    }

    /**
     * Checks if the given offset exists.
     *
     * @param string $field
     * @return boolean
     */
    public function hasField($field)
    {
        return in_array($field, $this->structure);
    }

    /**
     * Retrieves data.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->data;
    }

    /**
     * Sets the container's parameters from given array.
     *
     * @param array $fieldData
     */
    public function updateFields(array $fieldData)
    {
        foreach ($fieldData as $field => $value) {
            $this->setField($field, $value);
        }
    }

    /**
     * Returns this model's structure.
     *
     * @return array
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Removes all values.
     */
    public function clear()
    {
        $this->fillData([]);
    }

}
