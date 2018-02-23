<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="auth_tokens",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="auth_tokens_value_unique", columns={"value"})}
 * )
 */
class AuthToken
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    protected $id;

    /**
    * @ORM\Column(type="string")
    */
    protected $value;

    /**
    * @ORM\Column(type="datetime")
    * @var \DateTime
    */
    protected $createdAt;

    /**
    * @ORM\ManyToOne(targetEntity="User")
    * @var User
    */
    protected $user;

    /**
    * @ORM\Column(type="text")
    */
    protected $serializedSecurityContext;

    /**
     * The security context array not serialized.
     * @return array
     */
    protected $securityContext;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getSecurityContext() {
        if(!$this->securityContext){
            $this->securityContext = (array) json_decode($this->serializedSecurityContext);
        }
        return $this->securityContext;
    }

    public function setSecurityContext($securityContext) {
        $this->securityContext = $securityContext;
        $this->serializedSecurityContext = json_encode($securityContext);
        return $this;
    }

}
