<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanExtensionBundle\User;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Stores and update changable user details.
 *
 * @author Gabe <hgabka@gmail.com>
 */
class UserUpdater
{
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $password;

    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user = null)
    {
        if ($user) {
            $this->setUsername($user->getUsername());
            $this->setEmail($user->getEmail());
        }
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param UserUpdater $update
     *
     * @return array
     */
    public function getChanged(UserUpdater $update)
    {
        $changed = [];
        $ref = new \ReflectionClass($this);
        foreach ($ref->getProperties() as $prop) {
            $method = 'get'.ucfirst($prop->getName());
            $newVal = $update->$method();
            if ($this->$method() !== $newVal) {
                $changed[$prop->getName()] = $newVal;
            }
        }

        return $changed;
    }

    /**
     * @param UserInterface       $user
     * @param UserPasswordEncoder $encoder
     */
    public function updateUser(UserInterface $user, UserPasswordEncoder $encoder)
    {
        $user->setUsername($this->getUsername());
        $user->setEmail($this->getEmail());
        if ($this->getPassword()) {
            $user->setPassword($encoder->encodePassword($user, $this->getPassword()));
        }
    }
}
