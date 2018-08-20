<?php

namespace lOro\EntityBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * 
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PagosProveedoresRepository extends EntityRepository
{
    /**
      * Puede ser usado para ADMIN y PROVEEDORES
      */
    public function findUltimoMesPagos($proveedorId = null) {
        
        $conn = $this->getEntityManager()->getConnection();
      
        $config = $this->getEntityManager()->getConfiguration();
        $config->addCustomNumericFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');   
        $config->addCustomNumericFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');    
         
        $fechaActual = new \ DateTime('now');
        

        $queryWhere = ($proveedorId ? 'proveedor_id = '.$proveedorId : 'MONTH(vpp.fe_pago) = MONTH(CURRENT_TIMESTAMP()) AND YEAR(vpp.fe_pago) = YEAR(CURRENT_TIMESTAMP())');

        $queryMensual = "SELECT vpp. *
                         FROM v_listado_pagos_proveedores AS vpp
                         WHERE $queryWhere;";
        
        
        $stmt = $conn->executeQuery($queryMensual);
            
            
      return $stmt->fetchAll();   
    }
    
    public function buscarPagosPorNroReferencia($nroReferencia) {
        
        $conn = $this->getEntityManager()->getConnection();
      
        $query = "SELECT nro_referencia
                  FROM pagos_proveedores AS pp
                  WHERE pp.nro_referencia = '$nroReferencia';";
        
        
        $stmt = $conn->executeQuery($query);
            
            
      return $stmt->fetchAll();   
    }    
    
    public function buscarPorId($valorBusqueda)
    {      
      $valorBusqueda = ($valorBusqueda ? $valorBusqueda : '');
      
      $qb = $this->createQueryBuilder('u');
      $result = $qb ->select('u')
                    ->where(
                      $qb->expr()->like('u.id',$qb->expr()->literal('%'. $valorBusqueda . '%'))
                    )
                    ->orderBy('u.fePago','DESC')
       ->getQuery()   
       ->getResult();
       
      return $result;
    }
    
    public function buscarPorFechas($feInicio,$feFinal) 
    {
      return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                 FROM lOroEntityBundle:PagosProveedores e
                 WHERE (e.fePago
                 BETWEEN :feInicio AND :feFinal)
                 ORDER BY e.fePago DESC'
            )
            ->setParameter('feInicio', new \ DateTime($feInicio))
            ->setParameter('feFinal', new \ DateTime($feFinal))
            ->getResult();
      }
      
      public function buscarPorProveedor($idProveedor) {
          
      return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                 FROM lOroEntityBundle:PagosProveedores e
                 JOIN e.empresaPago AS ep
                 JOIN ep.proveedor AS p
                 WHERE p.id = :idProveedor
                 ORDER BY e.fePago DESC'
            )
            ->setParameter('idProveedor', $idProveedor)
            ->getResult();          
      }
}
