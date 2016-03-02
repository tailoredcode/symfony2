<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Meal;
use Doctrine\ORM\EntityRepository;

class VoteRepository extends EntityRepository
{


    /**
     * @param $cookie
     * @param \AppBundle\Entity\Meal $meal
     * @return \AppBundle\Entity\Vote
     */
	public function getVote($cookie, Meal $meal)
	{
		$query = $this -> createQueryBuilder('v')
            -> orderBy('v.id', 'DESC')
            -> andWhere('v.cookie = :cookie')
            -> andWhere('v.meal = :meal')
            -> andWhere('v.type = :type')
            -> setMaxResults(1)
            
            -> setParameters(array(
                'cookie' => $cookie,
                'meal' => $meal -> getId(),
                'type' => 'correct'
            ))
        ;

        return $query -> getQuery() -> getOneOrNullResult();
    }


    /**
     * @param $cookie
     * @return \AppBundle\Entity\Vote
     */
	public function getByCookie($cookie)
	{
		$query = $this -> createQueryBuilder('v')
            -> orderBy('v.id', 'DESC')
            -> andWhere('v.cookie = :cookie')
            -> setMaxResults(1)
            
            -> setParameters(array(
                'cookie' => $cookie,
            ))
        ;

        return $query -> getQuery() -> getOneOrNullResult();
    }


    /**
     * @param \DateTime $createdAt
     * @param $cookie
     * @return \AppBundle\Entity\Vote
     */
    public function getTodayVote(\DateTime $createdAt, $cookie)
    {
        $query = $this -> createQueryBuilder('v')
            -> orderBy('v.id', 'DESC')
            -> andWhere('v.createdAt LIKE :createdAt')
            -> andWhere('v.cookie = :cookie')
            -> andWhere('v.type = :type')
            -> setMaxResults(1)

            -> setParameters(array(
                'createdAt' => '%'.$createdAt -> format('Y-m-d').'%',
                'cookie' => $cookie,
                'type' => 'correct',
            ))
        ;

        return $query -> getQuery() -> getOneOrNullResult();
    }


}