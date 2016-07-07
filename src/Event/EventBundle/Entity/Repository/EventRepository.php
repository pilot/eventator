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

    public function getProgram($host)
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->select('p.startDate, p.link, p.endDate, p.title, s.language as speech_language, s.title as speech_title, sp.homepage as speaker_homepage, sp.firstName as speaker_firsName, sp.lastName as speaker_lastName, sp.id as speaker_id, ' . $qb->expr()->concat($qb->expr()->concat('sp.firstName', $qb->expr()->literal(' ')), 'sp.lastName') . ' as speaker_fullName')
            ->leftJoin('e.program', 'p')
            ->leftJoin('p.speech', 's')
            ->leftJoin('s.speaker', 'sp')
            ->where('e.host = :host')
            ->setParameter('host', 'http://'.$host)
            ->orderBy('p.startDate', 'ASC')
            ->orderBy('p.endDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
