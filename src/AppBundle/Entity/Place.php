<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaceRepository")
 * @ORM\Table(name="place")
 */
class Place
{


    /**
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Id
    */
    protected $id = null;
    
    /**
    * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
    * @ORM\JoinColumns({
    * 	@ORM\JoinColumn(referencedColumnName="id", name="user_id", onDelete="CASCADE", nullable=false)
    * })
    */
    protected $user = null;
    
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Upload")
    * @ORM\JoinColumns({
    * 	@ORM\JoinColumn(referencedColumnName="id", name="photo_id", onDelete="NO ACTION", nullable=true)
    * })
    */
    protected $photo = null;
    
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location")
    * @ORM\JoinColumns({
    * 	@ORM\JoinColumn(referencedColumnName="id", name="location_id", onDelete="NO ACTION", nullable=false)
    * })
    */
    protected $location = null;
    
    /**
    * @ORM\Column(name="created_at", type="datetime", nullable=false)
    */
    protected $createdAt = null;
    
    /**
    * @ORM\Column(name="active", type="boolean", nullable=false)
    */
    protected $active = null;
    
    /**
    * @ORM\Column(name="name", type="string", nullable=false, length=200)
    */
    protected $name = null;
    
    /**
    * @ORM\Column(name="name_en", type="string", nullable=false, length=200)
    */
    protected $nameEn = null;
    
    /**
    * @ORM\Column(name="description", type="text", nullable=true)
    */
    protected $description = null;
    
    /**
    * @ORM\Column(name="description_en", type="text", nullable=true)
    */
    protected $descriptionEn = null;
    
    /**
    * @ORM\Column(name="delivery", type="boolean", nullable=false)
    */
    protected $delivery = null;
    
    /**
    * @ORM\Column(name="local", type="boolean", nullable=false)
    */
    protected $local = null;
    
    /**
    * @ORM\Column(name="hours_pl", type="string", nullable=false, length=255)
    */
    protected $hoursPl = null;
    
    /**
    * @ORM\Column(name="hours_en", type="string", nullable=false, length=255)
    */
    protected $hoursEn = null;
    
    /**
    * @ORM\Column(name="website", type="string", nullable=true, length=255)
    */
    protected $website = null;
    
    /**
    * @ORM\Column(name="contact_number", type="string", nullable=false, length=45)
    */
    protected $contactNumber = null;
    
    /**
    * @ORM\Column(name="address", type="text", nullable=false)
    */
    protected $address = null;
    
    /**
    * @ORM\Column(name="long_", type="string", nullable=true, length=45)
    */
    protected $long = null;
    
    /**
    * @ORM\Column(name="lat_", type="string", nullable=true, length=45)
    */
    protected $lat = null;
    
    /**
    * @ORM\Column(name="seo_title", type="string", nullable=true, length=255)
    */
    protected $seoTitle = null;

    /**
    * @ORM\Column(name="seo_slug", type="string", nullable=true, length=255)
    */
    protected $seoSlug = null;
    
    /**
    * @ORM\Column(name="seo_description", type="text", nullable=true)
    */
    protected $seoDescription = null;
    
    /**
    * @ORM\Column(name="seo_keywords", type="text", nullable=true)
    */
    protected $seoKeywords = null;
    
    /**
    * @ORM\Column(name="saturday", type="boolean", nullable=false)
    */
    protected $saturday = null;
    
    /**
    * @ORM\Column(name="sunday", type="boolean", nullable=false)
    */
    protected $sunday = null;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Attachment", mappedBy="place", cascade={"persist","remove"})
     */
	protected $attachments = null;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Meal", mappedBy="place", cascade={"persist","remove"})
     */
	protected $meals = null;

	public function __construct()
	{
        $this -> createdAt = new \DateTime();
        $this -> attachments = new ArrayCollection();
        $this -> meals = new ArrayCollection();
        $this -> seoSlug = 'seo';
    }

    public function __toString()
    {
        return (string)$this -> getName();
    }

    public function toArray()
    {
        return array(
            'id' => $this -> getId(),
            'user' => $this -> getUser() -> toArray(),
            'photo' => $this -> getPhoto() -> toArray(),
            'location' => $this -> getLocation() -> toArray(),
            'createdAt' => $this -> getCreatedAt(),
            'active' => $this -> getActive(),
            'name' => $this -> getName(),
            'nameEn' => $this -> getNameEn(),
            'description' => $this -> getDescription(),
            'descriptionEn' => $this -> getDescriptionEn(),
            'delivery' => $this -> getDelivery(),
            'local' => $this -> getLocal(),
            'hoursPl' => $this -> getHoursPl(),
            'hoursEn' => $this -> getHoursEn(),
            'website' => $this -> getWebsite(),
            'contactNumber' => $this -> getContactNumber(),
            'address' => $this -> getAddress(),
            'long' => $this -> getLong(),
            'lat' => $this -> getLat(),
            'seoTitle' => $this -> getSeoTitle(),
            'seoDescription' => $this -> getSeoDescription(),
            'seoKeywords' => $this -> getSeoKeywords(),
            'saturday' => $this -> getSaturday(),
            'sunday' => $this -> getSunday(),
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
     * @return Place
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
     * @return Place
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

    /**
     * Set name
     *
     * @param string $name
     * @return Place
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
     * Set nameEn
     *
     * @param string $nameEn
     * @return Place
     */
    public function setNameEn($nameEn)
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    /**
     * Get nameEn
     *
     * @return string 
     */
    public function getNameEn()
    {
        return $this->nameEn;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Place
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set descriptionEn
     *
     * @param string $descriptionEn
     * @return Place
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    /**
     * Get descriptionEn
     *
     * @return string 
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * Set delivery
     *
     * @param boolean $delivery
     * @return Place
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return boolean 
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set local
     *
     * @param boolean $local
     * @return Place
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Get local
     *
     * @return boolean 
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set hoursPl
     *
     * @param string $hoursPl
     * @return Place
     */
    public function setHoursPl($hoursPl)
    {
        $this->hoursPl = $hoursPl;

        return $this;
    }

    /**
     * Get hoursPl
     *
     * @return string 
     */
    public function getHoursPl()
    {
        return $this->hoursPl;
    }

    /**
     * Set hoursEn
     *
     * @param string $hoursEn
     * @return Place
     */
    public function setHoursEn($hoursEn)
    {
        $this->hoursEn = $hoursEn;

        return $this;
    }

    /**
     * Get hoursEn
     *
     * @return string 
     */
    public function getHoursEn()
    {
        return $this->hoursEn;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Place
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set contactNumber
     *
     * @param string $contactNumber
     * @return Place
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    /**
     * Get contactNumber
     *
     * @return string 
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Place
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set long
     *
     * @param string $long
     * @return Place
     */
    public function setLong($long)
    {
        $this->long = $long;

        return $this;
    }

    /**
     * Get long
     *
     * @return string 
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Place
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set seoTitle
     *
     * @param string $seoTitle
     * @return Place
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string 
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDescription
     *
     * @param string $seoDescription
     * @return Place
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * Get seoDescription
     *
     * @return string 
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Set seoKeywords
     *
     * @param string $seoKeywords
     * @return Place
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;

        return $this;
    }

    /**
     * Get seoKeywords
     *
     * @return string 
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * Set saturday
     *
     * @param boolean $saturday
     * @return Place
     */
    public function setSaturday($saturday)
    {
        $this->saturday = $saturday;

        return $this;
    }

    /**
     * Get saturday
     *
     * @return boolean 
     */
    public function getSaturday()
    {
        return $this->saturday;
    }

    /**
     * Set sunday
     *
     * @param boolean $sunday
     * @return Place
     */
    public function setSunday($sunday)
    {
        $this->sunday = $sunday;

        return $this;
    }

    /**
     * Get sunday
     *
     * @return boolean 
     */
    public function getSunday()
    {
        return $this->sunday;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Place
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
     * Set photo
     *
     * @param \AppBundle\Entity\Upload $photo
     * @return Place
     */
    public function setPhoto(\AppBundle\Entity\Upload $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \AppBundle\Entity\Upload 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set location
     *
     * @param \AppBundle\Entity\Location $location
     * @return Place
     */
    public function setLocation(\AppBundle\Entity\Location $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \AppBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Add attachments
     *
     * @param \AppBundle\Entity\Attachment $attachments
     * @return Place
     */
    public function addAttachment(\AppBundle\Entity\Attachment $attachments)
    {
        $this->attachments[] = $attachments;

        return $this;
    }

    /**
     * Remove attachments
     *
     * @param \AppBundle\Entity\Attachment $attachments
     */
    public function removeAttachment(\AppBundle\Entity\Attachment $attachments)
    {
        $this->attachments->removeElement($attachments);
    }

    /**
     * Get attachments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Add meals
     *
     * @param \AppBundle\Entity\Meal $meals
     * @return Place
     */
    public function addMeal(\AppBundle\Entity\Meal $meals)
    {
        $this->meals[] = $meals;

        return $this;
    }

    /**
     * Remove meals
     *
     * @param \AppBundle\Entity\Meal $meals
     */
    public function removeMeal(\AppBundle\Entity\Meal $meals)
    {
        $this->meals->removeElement($meals);
    }

    /**
     * Get meals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMeals()
    {
        return $this->meals;
    }

    /**
     * Set seoSlug
     *
     * @param string $seoSlug
     * @return Place
     */
    public function setSeoSlug($seoSlug)
    {
        $this->seoSlug = $seoSlug;

        return $this;
    }

    /**
     * Get seoSlug
     *
     * @return string 
     */
    public function getSeoSlug()
    {
        return $this->seoSlug;
    }
}
