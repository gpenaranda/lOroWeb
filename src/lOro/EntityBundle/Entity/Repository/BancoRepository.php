<?php

namespace lOro\EntityBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class BancoRepository extends EntityRepository
{
    public function findOneByLastRegistroBanco()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT b FROM lOroEntityBundle:Banco b ORDER BY b.id DESC'
            )
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
    
    public function findAllColumnasBancoMayoresA($id) {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT b FROM lOroEntityBundle:Banco b WHERE b.id > :id'
            )
            ->setParameter('id',$id)
            ->getResult();        
    }
    
    public function findColumnaBancoMenorA($id) {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT b FROM lOroEntityBundle:Banco b WHERE b.id < :id ORDER BY b.id DESC'
            )
            ->setParameter('id',$id)
            ->setMaxResults(1)
            ->getOneOrNullResult();       
    }    
    
}

