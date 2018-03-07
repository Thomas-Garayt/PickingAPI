<?php

namespace AppBundle\Entity\Course;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="course")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Course\CourseRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Course extends EntityBase {

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Preparation\Preparation")
    */
    private $preparation;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product\ProductPosition")
    */
    private $productPosition;

    /**
    * @ORM\Column(type="boolean")
    */
    private $stepValidated = false;

    /**
    * @ORM\Column(type="integer")
    */
    private $quantity;

    /**
     * Get the value of Preparation
     *
     * @return mixed
     */
    public function getPreparation()
    {
        return $this->preparation;
    }

    /**
     * Set the value of Preparation
     *
     * @param mixed preparation
     *
     * @return self
     */
    public function setPreparation($preparation)
    {
        $this->preparation = $preparation;

        return $this;
    }

    /**
     * Get the value of Product Position
     *
     * @return mixed
     */
    public function getProductPosition()
    {
        return $this->productPosition;
    }

    /**
     * Set the value of Product Position
     *
     * @param mixed productPosition
     *
     * @return self
     */
    public function setProductPosition($productPosition)
    {
        $this->productPosition = $productPosition;

        return $this;
    }

    /**
     * Get the value of Step Validated
     *
     * @return mixed
     */
    public function getStepValidated()
    {
        return $this->stepValidated;
    }

    /**
     * Set the value of Step Validated
     *
     * @param mixed stepValidated
     *
     * @return self
     */
    public function setStepValidated($stepValidated)
    {
        $this->stepValidated = $stepValidated;

        return $this;
    }


    /**
     * Get the value of Quantity
     *
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the value of Quantity
     *
     * @param mixed quantity
     *
     * @return self
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

}
