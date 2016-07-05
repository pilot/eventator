<?php

namespace Event\EventBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Event\EventBundle\Entity\Event;

/**
 * CallForPaperRepository
 */
class CallForPaperRepository extends EntityRepository
{
    /**
     * @param Event   $event
     * @param string  $searchQuery
     * @param array   $order
     * @param integer $start
     * @param integer $length
     *
     * @return array
     */
    public function getCallsByParameters(Event $event, $searchQuery, $order, $start, $length)
    {
        $qb = $this ->createQueryBuilder('c');

        if (count($order)) {
            for ($i = 0, $ien = count($order); $i < $ien; $i++) {
                switch ($order[$i]['column']) {
                    case '0':
                        $qb->addOrderBy('c.id', $order[$i]['dir']);
                        break;
                    case '1':
                        $qb->addOrderBy('c.title', $order[$i]['dir']);
                        break;
                    case '2':
                        $qb->addOrderBy('c.language', $order[$i]['dir']);
                        break;
                    case '3':
                        $qb->addOrderBy('c.level', $order[$i]['dir']);
                        break;
                    case '4':
                        $qb->addOrderBy('c.status', $order[$i]['dir']);
                        break;
                    case '5':
                        $qb->addOrderBy('c.created', $order[$i]['dir']);
                        break;
                }
            }
        }

        $qb = $this->callsQueryAddParams($qb, $event, $searchQuery);

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
    public function getCallsCountByParameters(Event $event, $searchQuery)
    {
        $qb = $this ->createQueryBuilder('c')
            ->select('COUNT(DISTINCT c.id)');

        $qb = $this->callsQueryAddParams($qb, $event, $searchQuery);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Event        $event
     * @param QueryBuilder $qb
     * @param string       $searchQuery
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function callsQueryAddParams(QueryBuilder $qb, Event $event, $searchQuery)
    {
        if ($searchQuery != '') {
            $qb->where($qb->expr()->like('LOWER(c.title)', ':query'))
                ->orWhere($qb->expr()->like('LOWER(c.abstract)', ':query'));

            $qb->setParameter('query', '%'.mb_strtolower(trim($searchQuery), mb_detect_encoding($searchQuery)).'%');
        }

        if ($event) {
            $qb->andWhere('c.event = :event')
                ->setParameter('event', $event);
        }

        return $qb;
    }
}
