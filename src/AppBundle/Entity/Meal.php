<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MealRepository")
 * @ORM\Table(name="meal")
 */
class Meal
{


    /**
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Id
    */
    protected $id = null;
    
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Place")
    * @ORM\JoinColumns({
    * 	@ORM\JoinColumn(referencedColumnName="id", name="place_id", onDelete="NO ACTION", nullable=false)
    * })
    */
    protected $place = null;
    
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FoodImage")
    * @ORM\JoinColumns({
    * 	@ORM\JoinColumn(referencedColumnName="id", name="food_image_id", onDelete="NO ACTION", nullable=true)
    * })
    */
    protected $foodImage = null;
    
    /**
    * @ORM\Column(name="created_at", type="datetime", nullable=false)
    */
    protected $createdAt = null;
    
    /**
    * @ORM\Column(name="name_pl", type="string", nullable=false, length=200)
    */
    protected $namePl = null;
    
    /**
    * @ORM\Column(name="description_pl", type="text", nullable=false)
    */
    protected $descriptionPl = null;
    
    /**
    * @ORM\Column(name="name_en", type="string", nullable=true, length=200)
    */
    protected $nameEn = null;
    
    /**
    * @ORM\Column(name="description_en", type="text", nullable=true)
    */
    protected $descriptionEn = null;
    
    /**
    * @ORM\Column(name="price_pln", type="decimal", precision=10, scale=2, nullable=false)
    */
    protected $pricePln = null;

    
    /**
    * @ORM\Column(name="price_eur", type="decimal", precision=10, scale=2, nullable=true)
    */
    protected $priceEur = null;

    
    /**
    * @ORM\Column(name="price_gbp", type="decimal", precision=10, scale=2, nullable=true)
    */
    protected $priceGbp = null;

    
    /**
    * @ORM\Column(name="price_usd", type="decimal", precision=10, scale=2, nullable=true)
    */
    protected $priceUsd = null;

    
    /**
    * @ORM\Column(name="type", type="string", nullable=true, columnDefinition="ENUM('day','week','const')")
    */
    protected $type = null;
    
    /**
    * @ORM\Column(name="active_date", type="date", nullable=false)
    */
    protected $activeDate = null;
    
    /**
    * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
    */
    protected $deletedAt = null;
    
    /**
    * @ORM\Column(name="views", type="integer", nullable=false)
    */
    protected $views = null;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Vote", mappedBy="meal", cascade={"persist","remove"})
     */
    protected $votes = null;

    /**
     * @ORM\Column(name="numer_of_votes", type="integer", nullable=false)
     */
    protected $numberOfVotes = null;

    public function __construct()
	{
        $this -> createdAt = new \DateTime();
        $this -> activeDate = new \DateTime();
        $this -> views = 0;
        $this -> votes = new ArrayCollection();
        $this -> numberOfVotes  = 0;
    }

    public function __toString()
    {
        return (string)$this -> getName();
    }

    public function toArray()
    {
        return array(
            'id' => $this -> getId(),
            'place' => $this -> getPlace() -> toArray(),
            'foodImage' => $this -> getFoodImage() -> toArray(),
            'createdAt' => $this -> getCreatedAt(),
            'namePl' => $this -> getNamePl(),
            'descriptionPl' => $this -> getDescriptionPl(),
            'nameEn' => $this -> getNameEn(),
            'descriptionEn' => $this -> getDescriptionEn(),
            'pricePln' => $this -> getPricePln(),
            'priceEur' => $this -> getPriceEur(),
            'priceGbp' => $this -> getPriceGbp(),
            'priceUsd' => $this -> getPriceUsd(),
            'type' => $this -> getType(),
            'activeDate' => $this -> getActiveDate() -> format('Y-m-d'),
            'deletedAt' => $this -> getDeletedAt(),
            'views' => $this -> getViews(),
        );
    }

    public static function getAllTypeOptions()
    {
        return array('day','week','const');
    }

    public function hasAccess(\UserBundle\Entity\User $identity = null, $privilege = null, $throwException = false)
    {
        $hasAccess = false;
        if(is_object($identity) && $identity === $this -> getPlace() -> getUser())
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
     * @return Meal
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
     * Set namePl
     *
     * @param string $namePl
     * @return Meal
     */
    public function setNamePl($namePl)
    {
        $this->namePl = $namePl;

        return $this;
    }

    /**
     * Get namePl
     *
     * @return string 
     */
    public function getNamePl()
    {
        return $this->namePl;
    }

    /**
     * Set descriptionPl
     *
     * @param string $descriptionPl
     * @return Meal
     */
    public function setDescriptionPl($descriptionPl)
    {
        $this->descriptionPl = $descriptionPl;

        return $this;
    }

    /**
     * Get descriptionPl
     *
     * @return string 
     */
    public function getDescriptionPl()
    {
        return $this->descriptionPl;
    }

    /**
     * Set nameEn
     *
     * @param string $nameEn
     * @return Meal
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
     * Set descriptionEn
     *
     * @param string $descriptionEn
     * @return Meal
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
     * Set pricePln
     *
     * @param string $pricePln
     * @return Meal
     */
    public function setPricePln($pricePln)
    {
        $this->pricePln = $pricePln;

        return $this;
    }

    /**
     * Get pricePln
     *
     * @return string 
     */
    public function getPricePln()
    {
        return $this->pricePln;
    }

    /**
     * Set priceEur
     *
     * @param string $priceEur
     * @return Meal
     */
    public function setPriceEur($priceEur)
    {
        $this->priceEur = $priceEur;

        return $this;
    }

    /**
     * Get priceEur
     *
     * @return string 
     */
    public function getPriceEur()
    {
        return $this->priceEur;
    }

    /**
     * Set priceGbp
     *
     * @param string $priceGbp
     * @return Meal
     */
    public function setPriceGbp($priceGbp)
    {
        $this->priceGbp = $priceGbp;

        return $this;
    }

    /**
     * Get priceGbp
     *
     * @return string 
     */
    public function getPriceGbp()
    {
        return $this->priceGbp;
    }

    /**
     * Set priceUsd
     *
     * @param string $priceUsd
     * @return Meal
     */
    public function setPriceUsd($priceUsd)
    {
        $this->priceUsd = $priceUsd;

        return $this;
    }

    /**
     * Get priceUsd
     *
     * @return string 
     */
    public function getPriceUsd()
    {
        return $this->priceUsd;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Meal
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
     * Set activeDate
     *
     * @param \DateTime $activeDate
     * @return Meal
     */
    public function setActiveDate($activeDate)
    {
        $this->activeDate = $activeDate;

        return $this;
    }

    /**
     * Get activeDate
     *
     * @return \DateTime 
     */
    public function getActiveDate()
    {
        return $this->activeDate;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Meal
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Meal
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set place
     *
     * @param \AppBundle\Entity\Place $place
     * @return Meal
     */
    public function setPlace(\AppBundle\Entity\Place $place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return \AppBundle\Entity\Place 
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set foodImage
     *
     * @param \AppBundle\Entity\FoodImage $foodImage
     * @return Meal
     */
    public function setFoodImage(\AppBundle\Entity\FoodImage $foodImage = null)
    {
        $this->foodImage = $foodImage;

        return $this;
    }

    /**
     * Get foodImage
     *
     * @return \AppBundle\Entity\FoodImage 
     */
    public function getFoodImage()
    {
        return $this->foodImage;
    }

    /**
     * Set numberOfVotes
     *
     * @param integer $numberOfVotes
     * @return Meal
     */
    public function setNumberOfVotes($numberOfVotes)
    {
        $this->numberOfVotes = $numberOfVotes;

        return $this;
    }

    /**
     * Get numberOfVotes
     *
     * @return integer 
     */
    public function getNumberOfVotes()
    {
        return $this->numberOfVotes;
    }

    /**
     * Add votes
     *
     * @param \AppBundle\Entity\Vote $votes
     * @return Meal
     */
    public function addVote(\AppBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \AppBundle\Entity\Vote $votes
     */
    public function removeVote(\AppBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
