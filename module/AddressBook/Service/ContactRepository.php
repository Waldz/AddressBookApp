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
     * Retrieves contacts by given filters
     *
     * @param array $supervisorIds
     * @return Contact[]
     */
    public function contactList(array $supervisorIds = [])
    {
        $db = $this->getDatabaseDriver();

        $filters = [1];
        if (isset($supervisorIds)) {
            $supervisorIds[] = -1;
            $ids = array_filter($supervisorIds, array($db, 'quote'));
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
     * @param array $row
     * @return Contact
     */
    private function rowToContact($row)
    {
        return new Contact($row);
    }

}
