<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewsletterRepository")
 * @ORM\Table(name="newsletter")
 */
class Newsletter
{


    /**
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Id
    */
    protected $id = null;
    
    /**
    * @ORM\Column(name="email", type="string", nullable=false, length=255)
    */
    protected $email = null;
    
    /**
    * @ORM\Column(name="created_at", type="datetime", nullable=false)
    */
    protected $createdAt = null;
    
    /**
    * @ORM\Column(name="active", type="boolean", nullable=false)
    */
    protected $active = null;

	public function __construct()
    {
        $this -> active = 1;
        $this -> createdAt = new \DateTime();

    }

    public function __toString()
    {
        return (string)$this -> getName();
    }

    public function toArray()
    {
        return array(
            'id' => $this -> getId(),
            'email' => $this -> getEmail(),
            'createdAt' => $this -> getCreatedAt() -> format('Y-m-d H:i:s'),
            'active' => $this -> getActive(),
        );
    }




    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Newsletter
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Newsletter
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Newsletter
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
}
