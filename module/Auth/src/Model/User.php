<?php

namespace Auth\Model;

use Application\Db\Model\DatabaseModel;

/**
 * Data model to work with auth user entries
 *
 * @package Auth
 * @subpackage Model
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class User extends DatabaseModel
{

    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Array of fields that this model has.
     *
     * @var array
     */
    protected $structure = array(
        'id',
        'email',
        'password_hash',
        'status',
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
     * @return User
     */
    public function setId($id)
    {
        $this->setField('id', $id);

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
     * @return User
     */
    public function setEmail($email)
    {
        $this->setField('email', $email);

        return $this;
    }

    /**
     * Retrieves password_hash.
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->getField('password_hash');
    }

    /**
     * Sets password_hash.
     *
     * @param string $passwordHash
     * @return User
     */
    public function setPasswordHash($passwordHash)
    {
        $this->setField('password_hash', $passwordHash);

        return $this;
    }

    /**
     * Retrieves status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getField('status');
    }

    /**
     * Sets status.
     *
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->setField('status', $status);

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->getStatus() === self::STATUS_ACTIVE;
    }

}
