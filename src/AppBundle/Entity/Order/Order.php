<?php

namespace AppBundle\Entity\Order;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Order\OrderRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Order extends EntityBase {

    /**
    * @ORM\Column(type="string")
    */
    private $reference;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer\Customer")
    */
    private $customer;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Order\OrderProduct", mappedBy="order")
    */
    private $products;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Preparation\PreparationOrder", mappedBy="order")
    */
    private $preparations;

    /**
    * @ORM\Column(type="string")
    */
    private $status;

    /**
    * @ORM\Column(type="decimal", scale=2)
    */
    private $price;

    /**
    * @ORM\Column(type="decimal", scale=2)
    */
    private $weight;


    /**
     * Get the value of Customer
     *
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set the value of Customer
     *
     * @param mixed customer
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the value of Products
     *
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of Products
     *
     * @param mixed products
     *
     * @return self
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get the value of Preparations
     *
     * @return mixed
     */
    public function getPreparations()
    {
        return $this->preparations;
    }

    /**
     * Set the value of Preparations
     *
     * @param mixed preparations
     *
     * @return self
     */
    public function setPreparations($preparations)
    {
        $this->preparations = $preparations;

        return $this;
    }


    /**
     * Get the value of Status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if (OrderStatus::isValidValue($status, false)) {
            $this->status = $status;
            return $this;
        } else {
            throw new \InvalidArgumentException('setStatus only accept arguments of type OrderStatus');
        }
    }


    /**
     * Get the value of Reference
     *
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set the value of Reference
     *
     * @param mixed reference
     *
     * @return self
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

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
     * Get the value of Weight
     *
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the value of Weight
     *
     * @param mixed weight
     *
     * @return self
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

}
