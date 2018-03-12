<?php

namespace AppBundle\Entity\Preparation;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="preparation")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Preparation\PreparationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Preparation extends EntityBase {

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    */
    private $user;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $startTime;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $endTime;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Preparation\PreparationOrder", mappedBy="preparation")
    */
    private $orders;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Course\Course", mappedBy="preparation")
    */
    private $courses;

    /**
     * Get the value of User
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of User
     *
     * @param mixed user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of Start Time
     *
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set the value of Start Time
     *
     * @param mixed startTime
     *
     * @return self
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get the value of End Time
     *
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set the value of End Time
     *
     * @param mixed endTime
     *
     * @return self
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get the value of Orders
     *
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set the value of Orders
     *
     * @param mixed orders
     *
     * @return self
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * Get the value of Courses
     *
     * @return mixed
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Set the value of Courses
     *
     * @param mixed courses
     *
     * @return self
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;

        return $this;
    }

}
