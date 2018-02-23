<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints As Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User extends EntityBase implements UserInterface {

    /**
    * The personnal ID of the user.
    * @ORM\Column(type="string")
    */
    private $identifier;

    /**
    * @ORM\Column(type="string")
    */
    private $firstname;

    /**
    * @ORM\Column(type="string")
    */
    private $lastname;

    /**
    * @ORM\Column(type="string")
    */
    private $email;

    /**
    * @ORM\Column(type="string")
    */
    private $password;
    private $plainPassword;

    /**
    * @ORM\Column(type="string", unique=true)
    */
    private $username;

    /**
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\User\UserCaracteristic")
    */
    private $caracteristic;

    // Use by Symfony - do not remove
    public function getRoles() {
        return [];
    }
    // Use by Symfony - do not remove
    public function getSalt() {
        return null;
    }
    // Use by Symfony - do not remove
    public function eraseCredentials() {
        $this->plainPassword = null;
    }

    /**
     * Get the value of The personnal ID of the user.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set the value of The personnal ID of the user.
     *
     * @param mixed identifier
     *
     * @return self
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get the value of Firstname
     *
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of Firstname
     *
     * @param mixed firstname
     *
     * @return self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of Lastname
     *
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of Lastname
     *
     * @param mixed lastname
     *
     * @return self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of Email
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of Email
     *
     * @param mixed email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of Password
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of Password
     *
     * @param mixed password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of Plain Password
     *
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of Plain Password
     *
     * @param mixed plainPassword
     *
     * @return self
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get the value of Username
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of Username
     *
     * @param mixed username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of Caracteristic
     *
     * @return mixed
     */
    public function getCaracteristic()
    {
        return $this->caracteristic;
    }

    /**
     * Set the value of Caracteristic
     *
     * @param mixed caracteristic
     *
     * @return self
     */
    public function setCaracteristic($caracteristic)
    {
        $this->caracteristic = $caracteristic;

        return $this;
    }

}
