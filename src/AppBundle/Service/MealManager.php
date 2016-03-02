<?php

namespace AppBundle\Service;

use AppBundle\Entity\Place;
use JMS\DiExtraBundle\Annotation\Service as JMSService;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

use AppBundle\Repository\MealRepository;
use AppBundle\Repository\PlaceRepository;
use UserBundle\Repository\UserRepository;
use AppBundle\Entity\Meal;

/**
 * @JMSService("service.mealManager")
 */
class MealManager
{
            
    /**
    * @var ContainerInterface
    */
    protected $containerInterface = null;

    /**
    * @var EntityManager
    */
    protected $entityManager = null;

                
    /**
     * @InjectParams({
     *    "containerInterface" = @Inject("service_container"),
     *    "entityManager" = @Inject("doctrine.orm.default_entity_manager")
     * })
    */
    public function __construct(ContainerInterface $containerInterface, EntityManager $entityManager)
    {
        $this -> containerInterface = $containerInterface;
        $this -> entityManager = $entityManager;
    }
                
    public function view(Meal $meal)
    {
        $meal -> setViews( $meal -> getViews() + 1);
        $this -> entityManager -> persist($meal);
    }
                
    public function delete(Meal $meal)
    {
        $this -> entityManager -> remove($meal);
    }
                
    public function create(Meal $meal, Place $place)
    {
        if(!$meal -> getType())
            $meal -> setType('day');

        $meal -> setPlace($place);
        $this -> entityManager -> persist($meal);
    }
    
    /**
     * @return MealRepository
    */
    protected function _getMealRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Meal");
    }

    /**
     * @return PlaceRepository
    */
    protected function _getPlaceRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Place");
    }

    /**
     * @return UserRepository
    */
    protected function _getUserRepository()
    {
        return $this -> entityManager -> getRepository("UserBundle:User");
    }


    
}
