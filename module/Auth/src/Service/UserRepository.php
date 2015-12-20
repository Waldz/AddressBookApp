<?php

namespace Auth\Service;

use Application\Db\Driver\DatabaseDriver;
use Auth\Model\User;

/**
 * Service responsible for user manipulations
 *
 * @package Auth
 * @subpackage Service
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class UserRepository
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
     * Retrieves user by ID
     *
     * @param int $id
     * @return User
     */
    public function userGet($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Please give me user ID');
        }
        $db = $this->getDatabaseDriver();

        $query = "
            SELECT *
            FROM `auth_user`
            WHERE `id` = ". $db->quote($id) ."
            LIMIT 1
        ";
        $row = $db->queryRow($query);
        if (isset($row)) {
            return $this->rowToUser($row);
        }

        return null;
    }

    /**
     * Retrieves user by given email
     *
     * @param string $email
     * @param string [$status]
     * @return User
     */
    public function userGetByEmail($email, $status = User::STATUS_ACTIVE)
    {
        if (empty($email)) {
            throw new \InvalidArgumentException('Please give me user email');
        }
        $db = $this->getDatabaseDriver();

        $query = "
            SELECT *
            FROM `auth_user`
            WHERE
                `email` = ". $db->quote($email) ."
                AND `status` = ". $db->quote($status) ."
            LIMIT 1
        ";
        $row = $db->queryRow($query);
        if (isset($row)) {
            return $this->rowToUser($row);
        }

        return null;
    }

    /**
     * @param array $row
     * @return User
     */
    private function rowToUser($row)
    {
        return new User($row);
    }

}
