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
     * @var Contact
     */
    private $supervisor = null;

    /**
     * @var Contact[]
     */
    private $supervisedContacts = [];

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
     * Check if has supervisor.
     *
     * @return bool
     */
    public function hasSupervisor()
    {
        $supervisorId = $this->getField('supervisor_contact_id');

        return isset($supervisorId);
    }

    /**
     * Retrieves supervisor_contact_id.
     *
     * @return string
     */
    public function getSupervisorId()
    {
        return $this->getField('supervisor_contact_id');
    }

    /**
     * Sets supervisor_contact_id.
     *
     * @param string $supervisorContactId
     * @return Contact
     */
    public function setSupervisorId($supervisorContactId)
    {
        $this->setField('supervisor_contact_id', $supervisorContactId);

        return $this;
    }

    /**
     * Retrieves supervisor.
     *
     * @return Contact
     */
    public function getSupervisor()
    {
        if (isset($this->supervisor) && $this->getSupervisorId() != $this->supervisor->getId()) {
            return null;
        }

        return $this->supervisor;
    }

    /**
     * Sets supervisor.
     *
     * @param Contact $supervisor
     * @return Contact
     */
    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;
        $this->setField(
            'supervisor_contact_id',
            isset($supervisor) ? $supervisor->getId() : null
        );

        return $this;
    }

    /**
     * Check if contact has supervisedContacts.
     *
     * @return bool
     */
    public function hasSupervisedContacts()
    {
        return count($this->supervisedContacts) > 0;
    }

    /**
     * Retrieves supervisedContacts.
     *
     * @return Contact[]
     */
    public function getSupervisedContacts()
    {
        return $this->supervisedContacts;
    }

    /**
     * Sets supervisedContacts.
     *
     * @param Contact[] $supervisedContacts
     *
     * @return Contact
     */
    public function setSupervisedContacts($supervisedContacts)
    {
        $this->supervisedContacts = $supervisedContacts;

        return $this;
    }

    /**
     * Add supervised contact.
     *
     * @param Contact $supervisedContact
     *
     * @return Contact
     */
    public function addSupervisedContact($supervisedContact)
    {
        $this->supervisedContacts[] = $supervisedContact;

        return $this;
    }

}
