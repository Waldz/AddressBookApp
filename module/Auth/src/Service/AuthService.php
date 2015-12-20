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

    const SESSION_USER_ID = 'authenticatedUserId';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var array
     */
    private $session;

    /**
     * @var User
     */
    private $authenticatedUser;

    /**
     * @param UserRepository $userRepository
     * @param array $session
     */
    public function __construct(UserRepository $userRepository, &$session)
    {
        $this->setUserRepository($userRepository);
        $this->session = &$session;
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
     * @return User|null Return authenticated user of NULL if failed
     */
    public function authenticate($email, $password)
    {
        if (empty($email)) {
            return null;
        }
        if (empty($password)) {
            return null;
        }

        $userCandidate = $this->getUserRepository()->userGetByEmail($email, User::STATUS_ACTIVE);
        if (!$userCandidate) {
            return null;
        }

        if (!$userCandidate->isActive()) {
            return false;
        }

        if ($userCandidate->getPasswordHash()!==$this->calculatePasswordHash($password)) {
            return null;
        }

        $this->storeAuthentication($userCandidate);

        return $userCandidate;
    }

    /**
     * Logouts currently authenticate user
     */
    public function unauthenticate()
    {
        $this->clearAuthentication();
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        $authenticatedUser = $this->getAuthenticatedUser();

        return isset($authenticatedUser);
    }

    /**
     * Retrieves currently logged user.
     *
     * @return User
     */
    public function getAuthenticatedUser()
    {
        if ($this->authenticatedUser) {
            return $this->authenticatedUser;
        }

        return $this->authenticateFromSession();
    }

    /**
     * @param User $user
     */
    protected function storeAuthentication(User $user)
    {
        $this->authenticatedUser = $user;
        $this->session[self::SESSION_USER_ID] = $user->getId();
    }

    protected function clearAuthentication()
    {
        $this->authenticatedUser = null;
        unset($this->session[self::SESSION_USER_ID]);
    }

    /**
     * Tries to check and authenticate by session storage
     *
     * @return User|null Return authenticated user of NULL if failed
     */
    protected function authenticateFromSession()
    {
        if (empty($this->session[self::SESSION_USER_ID])) {
            return null;
        }

        $candidateUser = $this->getUserRepository()->userGet($this->session[self::SESSION_USER_ID]);
        if (!isset($candidateUser)) {
            return null;
        }

        if (!$candidateUser->isActive()) {
            return false;
        }

        $this->storeAuthentication($candidateUser);

        return $candidateUser;
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
