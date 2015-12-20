<?php

namespace AddressBook\Service;

use AddressBook\Model\Contact;
use Application\Db\Driver\DatabaseDriver;

/**
 * Service responsible for contact manipulations
 *
 * @package AddressBook
 * @subpackage Service
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class ContactRepository
{

    /**
     * DB driver
     *
     * @var DatabaseDriver
     */
    protected $databaseDriver;

    /**
     * Constructor
     *
     * @param DatabaseDriver $databaseDriver
     */
    public function __construct($databaseDriver)
    {
        $this->setDatabaseDriver($databaseDriver);
    }

    /**
     * @param DatabaseDriver $db
     */
    public function setDatabaseDriver($db)
    {
        $this->databaseDriver = $db;
    }

    /**
     * @return DatabaseDriver
     */
    public function getDatabaseDriver()
    {
        return $this->databaseDriver;
    }

    /**
     * Retrieves contact by ID
     *
     * @param int $id
     * @return Contact
     */
    public function contactGet($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Please give me contact ID');
        }
        $db = $this->getDatabaseDriver();

        $query = "
            SELECT *
            FROM `contact`
            WHERE `id` = ". $db->quote($id) ."
            LIMIT 1
        ";
        $row = $db->queryRow($query);
        if (isset($row)) {
            return $this->rowToContact($row);
        }

        return null;
    }

    /**
     * Save contact
     *
     * @param Contact $contact
     */
    public function contactSave(Contact $contact)
    {
        if (!empty($contact->getId())) {
            $this->contactUpdate($contact);
        } else {
            $this->contactInsert($contact);
        }
    }

    /**
     * Delete contact
     *
     * @param Contact $contact
     */
    public function contactDelete(Contact $contact)
    {
        $db = $this->getDatabaseDriver();

        $query = "
            DELETE FROM `contact`
            WHERE `id` = ". $db->quote($contact->getId()) ."
            LIMIT 1
        ";
        $db->query($query);
    }

    /**
     * Retrieves contacts by given filters
     *
     * @param array $supervisorIds
     * @return Contact[]
     */
    public function contactList(array $supervisorIds = null)
    {
        $db = $this->getDatabaseDriver();

        $filters = [1];
        if (isset($supervisorIds)) {
            $supervisorIds[] = -1;
            $ids = array_map(array($db, 'quote'), $supervisorIds);
            $filters[] = "`supervisor_contact_id` IN (". implode(', ', $ids) .")";
        }

        $query = "
            SELECT *
            FROM `contact`
            WHERE ". implode(' AND ', $filters)."
        ";
        $rows = $this->getDatabaseDriver()->queryAll($query);

        return array_map([$this, 'rowToContact'], $rows);
    }

    /**
     * Retrieves contacts by given $ids
     *
     * @param array $ids
     * @return Contact[]
     */
    public function contactListByIds(array $ids)
    {
        $db = $this->getDatabaseDriver();

        $ids[] = -1;
        $ids = array_map(array($db, 'quote'), $ids);

        $query = "
            SELECT *
            FROM `contact`
            WHERE `id` IN (". implode(', ', $ids) .")
        ";
        $rows = $this->getDatabaseDriver()->queryAll($query);

        return array_map([$this, 'rowToContact'], $rows);
    }

    /**
     * @return Contact[]
     */
    public function contactListWithoutSupervisor()
    {
        $query = "
            SELECT *
            FROM `contact`
            WHERE `supervisor_contact_id` IS NULL
        ";
        $rows = $this->getDatabaseDriver()->queryAll($query);

        return array_map([$this, 'rowToContact'], $rows);
    }

    /**
     * Fetch and append supervised people too each given contact
     *
     * @param Contact[] $contacts
     * @return Contact[]
     */
    public function fetchSupervisors($contacts)
    {
        $supervisorIds = [];
        foreach ($contacts as $contact) {
            $supervisorIds[] = $contact->getSupervisorId();
        }

        $supervisors = $this->contactListByIds($supervisorIds);
        $supervisorsById = [];
        foreach ($supervisors as $supervisor) {
            $supervisorsById[$supervisor->getId()] = $supervisor;
        }

        foreach ($contacts as $contact) {
            if (isset($supervisorsById[$contact->getSupervisorId()])) {
                $contact->setSupervisor($supervisorsById[$contact->getSupervisorId()]);
            }
        }

        return $contacts;
    }

    /**
     * Fetch and append supervised people too each given contact
     *
     * @param Contact[] $supervisors
     * @return Contact[]
     */
    public function fetchSupervisedPersons($supervisors)
    {
        $supervisorsById = [];
        $supervisorIds = [];
        foreach ($supervisors as $supervisor) {
            $supervisorsById[$supervisor->getId()] = $supervisor;
            $supervisorIds[] = $supervisor->getId();
        }

        $supervisedContacts = $this->contactList($supervisorIds);
        foreach ($supervisedContacts as $supervisedContact) {
            /** @var Contact $supervisor */
            $supervisor = $supervisorsById[$supervisedContact->getSupervisorId()];
            $supervisor->addSupervisedContact($supervisedContact);
        }

        foreach ($supervisors as $supervisor) {
            $this->fetchSupervisedPersons($supervisor->getSupervisedContacts());
        }

        return $supervisors;
    }

    /**
     * @param array $row
     * @return Contact
     */
    private function rowToContact($row)
    {
        return new Contact($row);
    }

    /**
     * Insert contact
     *
     * @param Contact $contact
     */
    protected function contactInsert(Contact $contact)
    {
        $db = $this->getDatabaseDriver();

        $parts = array();
        foreach ($contact->getFields() as $field => $fieldValue) {
            $parts[] = "`". $field ."` = ". $db->quote($fieldValue);
        }

        $query = "
            INSERT INTO `contact`
            SET
                `created_at` = NOW(),
                ". implode($parts, ', ') ."
        ";
        $db->query($query);

        $contact->setId($db->lastInsertId('contact', 'id'));
    }

    /**
     * Insert contact
     *
     * @param Contact $contact
     */
    protected function contactUpdate(Contact $contact)
    {
        $db = $this->getDatabaseDriver();

        $parts = array();
        foreach ($contact->getFields() as $field => $fieldValue) {
            $parts[] = "`". $field ."` = ". $db->quote($fieldValue);
        }

        $query = "
            UPDATE `contact`
            SET
                `updated_at` = NOW(),
                ". implode($parts, ', ') ."
            WHERE `id` = ". $db->quote($contact->getId()) ."
            LIMIT 1
        ";
        $db->query($query);
    }

}
