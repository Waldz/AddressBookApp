<?php

namespace Auth\Service;

use Auth\Model\User;

/**
 * Service responsible for user authentification
 *
 * @package Auth
 * @subpackage Service
 *
 * @author Valdas Petrulis <petrulis.valdas@gmail.com>
 */
class AuthService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var User
     */
    private $authenticatedUser;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->setUserRepository($userRepository);
    }

    /**
     * Retrieves userRepository.
     *
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->userRepository;
    }

    /**
     * Sets userRepository.
     *
     * @param UserRepository $userRepository
     * @return AuthService
     */
    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        return $this;
    }

    /**
     * Tries to check and authenticate by given credentials
     *
     * @param string $email
     * @param string $password
     *
     * @return User Return authenticated user of NULL if failed
     */
    public function authenticate($email, $password)
    {
        if (empty($email)) {
            return null;
        }
        if (empty($password)) {
            return null;
        }

        $user = $this->getUserRepository()->userGetByEmail($email, User::STATUS_ACTIVE);
        if (!$user) {
            return null;
        }

        if (!$user->isActive()) {
            return false;
        }

        if ($user->getPasswordHash()!==$this->calculatePasswordHash($password)) {
            return null;
        }

        $this->authenticatedUser = $user;

        return $user;
    }

    /**
     * User has loged in
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        return isset($this->authenticatedUser);
    }

    /**
     * Retrieves currentUser.
     *
     * @return User
     */
    public function getAuthenticatedUser()
    {
        return $this->authenticatedUser;
    }

    /**
     * @param string $passwordReal
     * @return string
     */
    protected function calculatePasswordHash($passwordReal)
    {
        return sha1($passwordReal);
    }

}
