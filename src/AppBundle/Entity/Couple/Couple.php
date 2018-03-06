<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 06/03/18
 * Time: 21:45
 */

namespace AppBundle\Entity\Couple;

use AppBundle\Entity\EntityBase;


use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Table(name="couple",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="p1_p2_unique",columns={"p1","p2"})
 * })
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Couple\CoupleRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Couple extends EntityBase {

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product\Product")
     * @ORM\JoinColumn(name="p1", referencedColumnName="id", nullable=false)
     */
    private $p1;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product\Product")
     * @ORM\JoinColumn(name="p2", referencedColumnName="id", nullable=false)
     */
    private $p2;

    /**
     * @ORM\Column(type="integer")
     */
    private $total = 0;

    /**
     * @return mixed
     */
    public function getP1()
    {
        return $this->p1;
    }

    /**
     * @param mixed $p1
     */
    public function setP1($p1): void
    {
        $this->p1 = $p1;
    }

    /**
     * @return mixed
     */
    public function getP2()
    {
        return $this->p2;
    }

    /**
     * @param mixed $p2
     */
    public function setP2($p2): void
    {
        $this->p2 = $p2;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
    }

}