<?php

namespace AppBundle\Entity\Notification;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="notification")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Notification\NotificationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Notification extends EntityBase {

    /**
    * @ORM\Column(type="string")
    */
    private $type;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product\ProductPosition", mappedBy="id")
    */
    private $productPosition;


    /**
     * Get the value of Type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of Type
     *
     * @param mixed type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

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

}
