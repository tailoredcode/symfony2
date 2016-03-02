<?php

namespace AppBundle\Service;

use JMS\DiExtraBundle\Annotation\Service as JMSService;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Swift_Message;
use Doctrine\ORM\EntityManager;

use AppBundle\Repository\VoteRepository;
use AppBundle\Repository\MealRepository;
use AppBundle\Entity\Vote;
use AppBundle\Entity\Meal;

/**
 * @JMSService("service.voteManager")
 */
class VoteManager
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

    public function vote($ip, $cookie, Meal $meal)
    {
        $vote = new Vote();
        $vote -> setType('correct');

        if($vote -> getCreatedAt() -> format('Y-m-d') != $meal -> getActiveDate() -> format('Y-m-d'))
            return -2;

        $vote
            -> setIp($ip)
            -> setCookie($cookie)
            -> setMeal($meal)
            ;

        $todayVote = $this -> _getVoteRepository() -> getTodayVote(new \DateTime(), $cookie);

        if(is_object($todayVote))
        {
            //make prev vote invalid
            $todayVote -> getMeal() -> setNumberOfVotes($todayVote -> getMeal() -> getNumberOfVotes() -1);
            $todayVote -> setType('fake');
            $this -> entityManager -> persist($todayVote);
            $this -> entityManager -> persist($todayVote -> getMeal());
        }

        if($vote -> getType() != 'fake')
            $meal -> setNumberOfVotes($meal -> getNumberOfVotes() + 1);
    
        $this -> entityManager -> persist($vote);
        $this -> entityManager -> persist($meal);

        return true;
    }
    
    /**
     * @return VoteRepository
    */
    protected function _getVoteRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Vote");
    }

    /**
     * @return MealRepository
    */
    protected function _getMealRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Meal");
    }


    
}
