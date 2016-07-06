<?php

namespace Event\EventBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * ProgramRepository
 */
class ProgramRepository extends EntityRepository
{
    /**
     * @param integer $eventId
     * @param string  $searchQuery
     * @param array   $order
     * @param integer $start
     * @param integer $length
     *
     * @return array
     */
    public function getProgramByParameters($eventId, $searchQuery, $order, $start, $length)
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.events', 'e');

        if (count($order)) {
            for ($i = 0, $ien = count($order); $i < $ien; $i++) {
                switch ($order[$i]['column']) {
                    case '0':
                        $qb->addOrderBy('p.id', $order[$i]['dir']);
                        break;
                    case '1':
                        $qb->addOrderBy('p.title', $order[$i]['dir']);
                        break;
                    case '2':
                        $qb->addOrderBy('p.startDate', $order[$i]['dir']);
                        break;
                }
            }
        }

        $qb = $this->programQueryAddParams($qb, $eventId, $searchQuery);

        $qb->setFirstResult($start)
            ->setMaxResults($length);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param integer $eventId
     * @param string  $searchQuery
     *
     * @return array
     */
    public function getProgramCountByParameters($eventId, $searchQuery)
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.events', 'e')
            ->select('COUNT(DISTINCT p.id)');

        $qb = $this->programQueryAddParams($qb, $eventId, $searchQuery);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param QueryBuilder $qb
     * @param integer      $eventId
     * @param string       $searchQuery
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function programQueryAddParams(QueryBuilder $qb, $eventId, $searchQuery)
    {
        if ($searchQuery != '') {
            $qb->where($qb->expr()->like('LOWER(p.title)', ':query'));

            $qb->setParameter('query', '%'.mb_strtolower(trim($searchQuery), mb_detect_encoding($searchQuery)).'%');
        }

        if ($eventId) {
            $qb->andWhere($qb->expr()->eq('e.id', ':eventId'))
                ->setParameter('eventId', $eventId);
        }

        return $qb;
    }
}
