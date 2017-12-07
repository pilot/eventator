<?php

namespace Event\EventBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Event\EventBundle\Entity\ShowBlocks;

class ShowBlocksRepository extends EntityRepository
{
    public function findOrCreate()
    {
        $entity = $this->findOneBy(array(), array('id' => 'ASC'));
        if(!$entity){
            $entity = new ShowBlocks();
            $this->_em->persist($entity);
            $this->_em->flush();
        }
        return $entity;
    }
}
