<?php

namespace Event\EventBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Event\EventBundle\Entity\Event;

/**
 * SpeechRepository
 */
class SpeechRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getSpeechesIdsArray()
    {
        return $this->createQueryBuilder('s')
            ->select('s.id')
            ->getQuery()->getArrayResult();
    }

    /**
     * @param Event   $event
     * @param string  $searchQuery
     * @param array   $order
     * @param integer $start
     * @param integer $length
     *
     * @return array
     */
    public function getSpeechesByParameters(Event $event, $searchQuery, $order, $start, $length)
    {
        $qb = $this ->createQueryBuilder('s')
            ->innerJoin('s.speaker', 'ssp');

        if (count($order)) {
            for ($i = 0, $ien = count($order); $i < $ien; $i++) {
                switch ($order[$i]['column']) {
                    case '0':
                        $qb->addOrderBy('s.id', $order[$i]['dir']);
                        break;
                    case '1':
                        $qb->addOrderBy('ssp.firstName', $order[$i]['dir']);
                        break;
                    case '2':
                        $qb->addOrderBy('s.title', $order[$i]['dir']);
                        break;
                }
            }
        }

        $qb = $this->speechesQueryAddParams($qb, $event, $searchQuery);

        $qb->setFirstResult($start)
            ->setMaxResults($length);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Event  $event
     * @param string $searchQuery
     *
     * @return array
     */
    public function getSpeechesCountByParameters(Event $event, $searchQuery)
    {
        $qb = $this ->createQueryBuilder('s')
            ->innerJoin('s.speaker', 'ssp')
            ->select('COUNT(DISTINCT s.id)');

        $qb = $this->speechesQueryAddParams($qb, $event, $searchQuery);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Event        $event
     * @param QueryBuilder $qb
     * @param string       $searchQuery
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function speechesQueryAddParams(QueryBuilder $qb, Event $event, $searchQuery)
    {
        if ($searchQuery != '') {
            $qb->where($qb->expr()->like('LOWER(s.title)', ':query'))
                ->orWhere($qb->expr()->like('LOWER(ssp.firstName)', ':query'))
                ->orWhere($qb->expr()->like('LOWER(ssp.lastName)', ':query'));

            $qb->setParameter('query', '%'.mb_strtolower(trim($searchQuery), mb_detect_encoding($searchQuery)).'%');
        }

        if ($event) {
            $qb->andWhere('s.event = :event')
                ->setParameter('event', $event);
        }

        return $qb;
    }
}
