<?php

namespace AppBundle\Entity\Product;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Product\ProductRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Product extends EntityBase {

    /**
     * @ORM\Column(type="string", length=13, unique=true)
     */
    private $ean13;


    /**
    * @ORM\Column(type="string")
    */
    private $description;

    /**
    * @ORM\Column(type="decimal", scale=2)
    */
    private $price;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $weight;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Order\OrderProduct", mappedBy="product")
    */
    private $orders;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product\ProductPosition", mappedBy="product")
    */
    private $positions;



    /**
     * Get the value of Description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of Description
     *
     * @param mixed description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of Price
     *
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of Price
     *
     * @param mixed price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

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
     * Get the value of Positions
     *
     * @return mixed
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * Set the value of Positions
     *
     * @param mixed positions
     *
     * @return self
     */
    public function setPositions($positions)
    {
        $this->positions = $positions;

        return $this;
    }

}
