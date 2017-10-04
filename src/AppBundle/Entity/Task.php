<?php

namespace AppBundle\Entity;


/**
 * Task
 */
class Task
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $deadline;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $priority;
    /**
     *
     */
    private $todo;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Task
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
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Task
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set priority
     *
     * @param string $priority
     *
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set todo
     *
     * @param \AppBundle\Entity\Todo $todo
     *
     * @return Task
     */
    public function setTodo(\AppBundle\Entity\Todo $todo = null)
    {
        $this->todo = $todo;

        return $this;
    }

    /**
     * Get todo
     *
     * @return \AppBundle\Entity\Todo
     */
    public function getTodo()
    {
        return $this->todo;
    }
}
