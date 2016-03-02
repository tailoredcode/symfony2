<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoomRepository")
 * @ORM\Table(name="room", uniqueConstraints = { @ORM\UniqueConstraint(name="room_id_UNIQUE_UNIQUE", columns={ "roomID" })})
 */
class Room
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
    * @ORM\Column(name="roomID", type="string", nullable=false, length=8)
    */
    protected $roomID = null;
    
    /**
    * @ORM\Column(name="name", type="string", nullable=false, length=45)
    */
    protected $name = null;
    
    /**
    * @ORM\Column(name="note", type="text", nullable=true)
    */
    protected $note = null;
    
    /**
    * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
    * @ORM\JoinColumns({
    * 	@ORM\JoinColumn(referencedColumnName="id", name="user_id", onDelete="CASCADE", nullable=false)
    * })
    */
    protected $user = null;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RoomMeal", mappedBy="room", cascade={"persist","remove"})
     */
	protected $roomMeals = null;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RoomUser", mappedBy="room", cascade={"persist","remove"})
     */
	protected $roomUsers = null;

	public function __construct()
	{
	
        $this -> createdAt = new \DateTime();

        $this -> roomMeals = new ArrayCollection();
        $this -> roomUsers = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this -> getName();
    }

    public function toArray()
    {
        return array(
            'id' => $this -> getId(),
            'createdAt' => $this -> getCreatedAt() -> format('Y-m-d H:i:s'),
            'roomID' => $this -> getRoomID(),
            'name' => $this -> getName(),
            'note' => $this -> getNote(),
            'user' => $this -> getUser() -> toArray(),
        );
    }

    public function hasAccess(\UserBundle\Entity\User $identity = null, $privilege = null, $throwException = false)
    {
        $hasAccess = false;
        if(is_object($identity) && $identity === $this -> getUser())
            $hasAccess = true;

        if($throwException && !$hasAccess)
            throw new AccessDeniedException();

        return $hasAccess;
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
     * @return Room
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
     * Set roomID
     *
     * @param string $roomID
     * @return Room
     */
    public function setRoomID($roomID)
    {
        $this->roomID = $roomID;

        return $this;
    }

    /**
     * Get roomID
     *
     * @return string 
     */
    public function getRoomID()
    {
        return $this->roomID;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Room
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Room
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Room
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add roomMeals
     *
     * @param \AppBundle\Entity\RoomMeal $roomMeals
     * @return Room
     */
    public function addRoomMeal(\AppBundle\Entity\RoomMeal $roomMeals)
    {
        $this->roomMeals[] = $roomMeals;

        return $this;
    }

    /**
     * Remove roomMeals
     *
     * @param \AppBundle\Entity\RoomMeal $roomMeals
     */
    public function removeRoomMeal(\AppBundle\Entity\RoomMeal $roomMeals)
    {
        $this->roomMeals->removeElement($roomMeals);
    }

    /**
     * Get roomMeals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoomMeals()
    {
        return $this->roomMeals;
    }

    /**
     * Add roomUsers
     *
     * @param \AppBundle\Entity\RoomUser $roomUsers
     * @return Room
     */
    public function addRoomUser(\AppBundle\Entity\RoomUser $roomUsers)
    {
        $this->roomUsers[] = $roomUsers;

        return $this;
    }

    /**
     * Remove roomUsers
     *
     * @param \AppBundle\Entity\RoomUser $roomUsers
     */
    public function removeRoomUser(\AppBundle\Entity\RoomUser $roomUsers)
    {
        $this->roomUsers->removeElement($roomUsers);
    }

    /**
     * Get roomUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoomUsers()
    {
        return $this->roomUsers;
    }
}
