<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Place;
use AppBundle\Entity\Location;
use Doctrine\ORM\EntityRepository;

class MealRepository extends EntityRepository
{


    /**
     * @param $type
     * @param \DateTime $activeDate
     * @param null $delivery
     * @param null $local
     * @param null $saturday
     * @param null $sunday
     * @param \AppBundle\Entity\Location $location
     * @return \Doctrine\ORM\Query
     */
	public function getList($type, \DateTime $activeDate, $delivery = null, $local = null, $saturday = null, $sunday = null, Location $location = null)
	{
		$query = $this -> createQueryBuilder('m')
            -> orderBy('m.numberOfVotes', 'DESC')
            -> andWhere('m.type = :type')
            -> andWhere('m.activeDate = :activeDate')
            -> andWhere('m.deletedAt IS NULL')
            -> leftJoin('m.place', 'p')
            -> addSelect('p')
            -> andWhere('p.active = :active')
            
            -> setParameters(array(
                'type' => $type,
                'activeDate' => $activeDate -> format('Y-m-d'),
                'active' => true,
            ))
        ;
        if(!is_null($delivery)){
            $query -> setParameter('delivery', $delivery);
            $query -> andWhere('p.delivery = :delivery');
        }

        if(!is_null($local)){
            $query -> setParameter('local', $local);
            $query -> andWhere('p.local = :local');
        }

        if(!is_null($saturday)){
            $query -> setParameter('saturday', $saturday);
            $query -> andWhere('p.saturday = :saturday');
        }

        if(!is_null($sunday)){
            $query -> setParameter('sunday', $sunday);
            $query -> andWhere('p.sunday = :sunday');
        }

        if(!is_null($location)){
            $query -> setParameter('location', $location);
            $query -> andWhere('p.location = :location');
        }



        return $query -> getQuery();
    }


    /**
     * @param $id
     * @return \AppBundle\Entity\Meal
     */
	public function getMeal($id)
	{
		$query = $this -> createQueryBuilder('m')
            -> orderBy('m.id', 'DESC')
            -> andWhere('m.id = :id')
                    -> andWhere('m.deletedAt IS NULL')
            
            -> setParameters(array(
                'id' => $id,
            ))
        ;

        return $query -> getQuery() -> getOneOrNullResult();
    }


    /**
     * @param $id
     * @param \DateTime $activeDate
     * @param \AppBundle\Entity\Place $place
     * @param null $type
     * @return \AppBundle\Entity\Meal[]
     */
    public function getMeals($id, \DateTime $activeDate, Place $place, $type = null)
    {
        $query = $this -> createQueryBuilder('m')
            -> orderBy('m.id', 'DESC')
            -> andWhere('m.id <> :id')
            -> andWhere('m.activeDate = :activeDate')
            -> andWhere('m.deletedAt IS NULL')
            -> andWhere('m.place = :place')

            -> setParameters(array(
                'id' => $id,
                'activeDate' => $activeDate -> format('Y-m-d'),
                'place' => $place,
            ))
        ;
        if(!is_null($type)){
            $query -> setParameter('type', $type);
            $query -> andWhere('m.type IN(:type)');
        }

        return $query -> getQuery() -> getResult();
    }


    /**
     * @param \DateTime $activeDate
     * @param \AppBundle\Entity\Place $place
     * @param null $type
     * @return \Doctrine\ORM\Query
     */
	public function getPanelList(\DateTime $activeDate, Place $place, $type = null)
	{
		$query = $this -> createQueryBuilder('m')
            -> orderBy('m.activeDate', 'ASC')
            -> andWhere('m.activeDate >= :activeDate')
            -> andWhere('m.deletedAt IS NULL')
            -> andWhere('m.place = :place')
            
            -> setParameters(array(
                'activeDate' => $activeDate -> format('Y-m-d'),
                'place' => $place,
            ))
        ;
        if(!is_null($type)){
            $query -> setParameter('type', $type);
            $query -> andWhere('m.type IN(:type)');
        }

        return $query -> getQuery();
    }


}