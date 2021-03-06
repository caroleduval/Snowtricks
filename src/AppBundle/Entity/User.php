<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Photo", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $photo;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set name
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setfirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set photo
     *
     * @param \appBundle\Entity\Photo $photo
     *
     * @return User
     */
    public function setPhoto(\appBundle\Entity\Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \appBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        $this->setUsername($email);
        return $this;
    }
}
