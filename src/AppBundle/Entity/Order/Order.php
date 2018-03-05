<?php

namespace AppBundle\Entity\Order;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="order")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Order\OrderRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Order extends EntityBase {

    /**
    * @ORM\Column(type="string")
    */
    private $orderNumber;

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
    // Enum

    /**
     * Get the value of Order Number
     *
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set the value of Order Number
     *
     * @param mixed orderNumber
     *
     * @return self
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

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

}
