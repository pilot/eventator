<?php

namespace Event\EventBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class EventRepository extends EntityRepository
{
    public function getEvent($host)
    {
        return $this
            ->createQueryBuilder('e')
            ->where('host = :host')
            ->setParameter('host', 'http://'.$host)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }
}
