<?php

namespace AppBundle\Entity\Preparation;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="preparation_order_relation")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Preparation\PreparationOrderRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class PreparationOrder extends EntityBase {

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order\Order")
    */
    private $order;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Preparation\Preparation")
    */
    private $preparation;


    /**
     * Get the value of Order
     *
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of Order
     *
     * @param mixed order
     *
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

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

}
