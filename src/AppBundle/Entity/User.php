<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser
{
    protected $id;

    private $firstName;

    private $lastName;

    private $displayName;

    private $registrationDate;

    private $todos;

    public function __construct()
    {
        parent::__construct();
        $this->todos = new ArrayCollection();
        $this->registrationDate = new DateTime();
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return ArrayCollection
     */
    public function getTodos()
    {
        return $this->todos;
    }

    /**
     * @param ArrayCollection $todos
     */
    public function setTodos($todos)
    {
        $this->todos = $todos;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationDate(): \DateTime
    {
        return $this->registrationDate;
    }


    public function setRegistrationDate()
    {
        $this->registrationDate = new \DateTime('now');
    }

}