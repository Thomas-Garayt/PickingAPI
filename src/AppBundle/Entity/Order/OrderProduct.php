<?php

namespace AppBundle\Entity\Order;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="order_product_relation")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Order\OrderProductRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class OrderProduct extends EntityBase {

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order\Order")
    */
    private $order;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product\Product")
    */
    private $product;

    /**
    * @ORM\Column(type="integer")
    */
    private $quantity;

    /**
    * Quantity recovered by the preparator
    * @ORM\Column(type="integer")
    */
    private $count;

    /**
    * @ORM\Column(type="boolean")
    */
    private $uncomplete;

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


    /**
     * Get the value of Quantity recovered by the preparator
     *
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set the value of Quantity recovered by the preparator
     *
     * @param mixed count
     *
     * @return self
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }


    /**
     * Get the value of Uncomplete
     *
     * @return mixed
     */
    public function getUncomplete()
    {
        return $this->uncomplete;
    }

    /**
     * Set the value of Uncomplete
     *
     * @param mixed uncomplete
     *
     * @return self
     */
    public function setUncomplete($uncomplete)
    {
        $this->uncomplete = $uncomplete;

        return $this;
    }

}
