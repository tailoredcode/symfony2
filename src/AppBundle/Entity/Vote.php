<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoteRepository")
 * @ORM\Table(name="vote")
 */
class Vote
{


    /**
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Id
    */
    protected $id = null;
    
    /**
    * @ORM\Column(name="created_at", type="datetime", nullable=false)
    */
    protected $createdAt = null;
    
    /**
    * @ORM\Column(name="ip", type="string", nullable=false, length=30)
    */
    protected $ip = null;
    
    /**
    * @ORM\Column(name="cookie", type="string", nullable=false, length=255)
    */
    protected $cookie = null;
    
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meal")
    * @ORM\JoinColumns({
    * 	@ORM\JoinColumn(referencedColumnName="id", name="meal_id", onDelete="CASCADE", nullable=false)
    * })
    */
    protected $meal = null;
    
    /**
    * @ORM\Column(name="type", type="string", nullable=false, columnDefinition="ENUM('correct','fake')")
    */
    protected $type = null;

	public function __construct()
	{
        $this -> createdAt = new \DateTime();
    }

    public function __toString()
    {
        return (string)$this -> getId();
    }

    public function toArray()
    {
        return array(
            'id' => $this -> getId(),
            'createdAt' => $this -> getCreatedAt() -> format('Y-m-d H:i:s'),
            'ip' => $this -> getIp(),
            'cookie' => $this -> getCookie(),
            'meal' => $this -> getMeal() -> toArray(),
            'type' => $this -> getType(),
        );
    }

    public static function getAllTypeOptions()
    {
        return array('correct','fake');
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Vote
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
     * Set ip
     *
     * @param string $ip
     * @return Vote
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set cookie
     *
     * @param string $cookie
     * @return Vote
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * Get cookie
     *
     * @return string 
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Vote
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set meal
     *
     * @param \AppBundle\Entity\Meal $meal
     * @return Vote
     */
    public function setMeal(\AppBundle\Entity\Meal $meal)
    {
        $this->meal = $meal;

        return $this;
    }

    /**
     * Get meal
     *
     * @return \AppBundle\Entity\Meal 
     */
    public function getMeal()
    {
        return $this->meal;
    }
}
