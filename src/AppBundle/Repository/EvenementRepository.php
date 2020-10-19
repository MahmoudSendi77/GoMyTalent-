<?php

namespace AppBundle\Repository;

/**
 * EvenementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EvenementRepository extends \Doctrine\ORM\EntityRepository
{
    public function getJsonList(){
      $res =  $this->createQueryBuilder('a')
            ->select('a.title as title , a.dateDebut as start , a.id as _id' )
            ->getQuery()
            ->getArrayResult();
        return $res;
    }
}
