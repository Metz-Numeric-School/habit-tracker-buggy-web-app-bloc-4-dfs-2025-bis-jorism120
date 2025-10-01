<?php
namespace App\Entity;

use App\Entity\AbstractEntity;

/**
 * Représente un utilisateur
 */
class User extends AbstractEntity 
{
    private $firstname;

    private $lastname;

    private $email;

    private $password;

    private $isadmin;

    /**
     * Get the value of firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     */
    public function setFirstname($firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     */
    public function setLastname($lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }


    /**
     * Get the value of isadmin
     */
    public function getIsadmin()
    {
        return $this->isadmin;
    }

    /**
     * Set the value of isadmin
     */
    public function setIsadmin($isadmin): self
    {
        $this->isadmin = $isadmin;

        return $this;
    }
}