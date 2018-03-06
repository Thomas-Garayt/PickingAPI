<?php

namespace AppBundle\Entity\Position;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use AppBundle\Entity\EntityBase;

/**
 * @ORM\Table(name="position")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Position\PositionRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Position extends EntityBase {

    /**
    * @ORM\Column(type="string")
    */
    private $name;

    /**
    * @ORM\Column(type="string")
    */
    private $lane;

    /**
    * @ORM\Column(type="string")
    */
    private $landmark;

    /**
    * @ORM\Column(type="string")
    */
    private $shelf;

    /**
    * @ORM\Column(type="string")
    */
    private $section;

    /**
    * @ORM\Column(type="boolean")
    */
    private $enable;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product\ProductPosition", mappedBy="position")
    */
    private $products;

    /**
     * Get the value of Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name
     *
     * @param mixed name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of Lane
     *
     * @return mixed
     */
    public function getLane()
    {
        return $this->lane;
    }

    /**
     * Set the value of Lane
     *
     * @param mixed lane
     *
     * @return self
     */
    public function setLane($lane)
    {
        $this->lane = $lane;

        return $this;
    }

    /**
     * Get the value of Landmark
     *
     * @return mixed
     */
    public function getLandmark()
    {
        return $this->landmark;
    }

    /**
     * Set the value of Landmark
     *
     * @param mixed landmark
     *
     * @return self
     */
    public function setLandmark($landmark)
    {
        $this->landmark = $landmark;

        return $this;
    }

    /**
     * Get the value of Shelf
     *
     * @return mixed
     */
    public function getShelf()
    {
        return $this->shelf;
    }

    /**
     * Set the value of Shelf
     *
     * @param mixed shelf
     *
     * @return self
     */
    public function setShelf($shelf)
    {
        $this->shelf = $shelf;

        return $this;
    }

    /**
     * Get the value of Section
     *
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set the value of Section
     *
     * @param mixed section
     *
     * @return self
     */
    public function setSection($section)
    {
        $this->section = $section;

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
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param mixed $enable
     */
    public function setEnable($enable): void
    {
        $this->enable = $enable;
    }


    

}
