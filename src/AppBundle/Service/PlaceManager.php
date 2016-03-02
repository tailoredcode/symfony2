<?php

namespace AppBundle\Service;

use AppBundle\Entity\Place;
use JMS\DiExtraBundle\Annotation\Service as JMSService;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Swift_Message;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\User;


/**
 * @JMSService("service.placeManager")
 */
class PlaceManager
{
            
    /**
    * @var EntityManager
    */
    protected $entityManager = null;

                
    /**
     * @InjectParams({
     *    "entityManager" = @Inject("doctrine.orm.default_entity_manager")
     * })
    */
    public function __construct(EntityManager $entityManager)
    {
        $this -> entityManager = $entityManager;
    }
                
    public function create(Place $place, User $user)
    {
        $place -> setUser($user);

        $this -> entityManager -> persist($place);
    }
}
