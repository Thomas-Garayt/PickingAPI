<?php

namespace AppBundle\Entity\Product;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="product_position_relation")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Product\ProductPositionRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ProductPosition extends EntityBase {

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Position\Position")
    */
    private $position;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product\Product")
    */
    private $product;

    /**
    * @ORM\Column(type="integer")
    */
    private $quantity;

    /**
     * Get the value of Position
     *
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set the value of Position
     *
     * @param mixed position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get the value of Product
     *
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set the value of Product
     *
     * @param mixed product
     *
     * @return self
     */
    public function setProduct($product)
    {
        $this->product = $product;

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
