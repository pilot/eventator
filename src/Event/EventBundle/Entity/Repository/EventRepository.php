<?php

namespace Event\EventBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class EventRepository extends EntityRepository
{   
    public function getEvent()
    {
        return $this
            ->createQueryBuilder('e')
            ->where('e.id IS NOT NULL')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}