<?php

namespace AddressBook\Model;

use Application\Db\Model\DatabaseModel;

/**
 * Data model to work with address book entries
 *
 * @package AddressBook
 * @subpackage Model
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class Contact extends DatabaseModel
{

    /**
     * Array of fields that this model has.
     *
     * @var array
     */
    protected $structure = array(
        'id',
        'title',
        'name',
        'email',
        'supervisor_contact_id'
    );

    /**
     * Retrieves id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->getField('id');
    }

    /**
     * Sets id.
     *
     * @param int $id
     * @return Contact
     */
    public function setId($id)
    {
        $this->setField('id', $id);

        return $this;
    }

    /**
     * Retrieves title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getField('title');
    }

    /**
     * Sets title.
     *
     * @param string $title
     * @return Contact
     */
    public function setTitle($title)
    {
        $this->setField('title', $title);

        return $this;
    }

    /**
     * Retrieves name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getField('name');
    }

    /**
     * Sets name.
     *
     * @param string $name
     * @return Contact
     */
    public function setName($name)
    {
        $this->setField('name', $name);

        return $this;
    }

    /**
     * Retrieves email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getField('email');
    }

    /**
     * Sets email.
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->setField('email', $email);

        return $this;
    }

    /**
     * Retrieves supervisor_contact_id.
     *
     * @return string
     */
    public function getSupervisorContactId()
    {
        return $this->getField('supervisor_contact_id');
    }

    /**
     * Sets supervisor_contact_id.
     *
     * @param string $supervisorContactId
     * @return Contact
     */
    public function setSupervisorContactId($supervisorContactId)
    {
        $this->setField('supervisor_contact_id', $supervisorContactId);

        return $this;
    }

}
